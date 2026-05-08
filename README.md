# luminix/frontend

Pacote Laravel que prepara e entrega os dados de inicialização (_boot_) necessários para os pacotes Luminix do frontend (JavaScript e Flutter).

O pacote coleta informações da aplicação — configurações, usuário autenticado, modelos e rotas disponíveis — e as disponibiliza via diretiva Blade `@luminixEmbed()`. Essas informações permitem que o frontend opere com uma API familiar ao Laravel, sem precisar reescrever manualmente cada endpoint ou modelo.

---

## Requisitos

- PHP `^8.2`
- Laravel `^11.0`
- [`luminix/backend`](https://github.com/luminix-cms/backend) `^1.0`

---

## Instalação

```bash
composer require luminix/frontend
```

O pacote é auto-descoberto pelo Laravel via `FrontendServiceProvider`.

---

## Configuração

Publique o arquivo de configuração com:

```bash
php artisan vendor:publish --tag=luminix-config
```

O arquivo será criado em `config/luminix/frontend.php`:

---

## Uso

### Diretiva `@luminixEmbed()`

Adicione a diretiva no layout principal da sua aplicação, dentro do `<body>`:

```blade
<!DOCTYPE html>
<html>
<head>...</head>
<body>
    @luminixEmbed()

    <div id="app"></div>
    <script src="/js/app.js"></script>
</body>
</html>
```

Isso renderiza um elemento oculto com os dados de boot em JSON, que o pacote frontend lerá na inicialização.

#### Exibindo erros de validação do Laravel

A diretiva aceita nomes de campos de formulário separados por `|`. Quando presentes, os erros de validação correspondentes são incluídos no elemento de embed, permitindo que o frontend os exiba:

```blade
@luminixEmbed('email|password')
```

---

## Dados de boot

Os dados entregues pelo `@luminixEmbed()` seguem esta estrutura:

```json
{
    "app": {
        "name": "Minha Aplicação",
        "env": "production",
        "debug": false,
        "url": "https://meusite.com",
        "locale": "pt_BR",
        "fallback_locale": "en"
    },
    "auth": {
        "user": { "id": 1, "name": "João" },
        "csrf": "token-aqui"
    },
    "manifest": {
        "models": { ... },
        "routes": { ... }
    }
}
```

> O campo `manifest` só é incluído quando `boot.includes_manifest` é `true`.

---

## Manifest

O manifest descreve os modelos e rotas da aplicação disponíveis para o frontend.

### Modelos

Cada modelo registrado no `luminix/backend` com o trait `LuminixModel` é representado com:

| Campo | Descrição |
|---|---|
| `attributes` | Atributos e tipos da coluna (via `spatie/laravel-model-info`) |
| `displayName` | Nome legível do modelo |
| `fillable` | Campos preenchíveis |
| `casts` | Casts definidos no modelo |
| `primaryKey` | Nome da chave primária |
| `labeledBy` | Campo usado como label do registro |
| `timestamps` | Se o modelo usa `created_at`/`updated_at` |
| `softDeletes` | Se o modelo usa exclusão suave |
| `relations` | Relações registradas no modelo |

### Rotas

Cada rota nomeada é representada como:

```json
{
    "users.index": ["/users", "get"],
    "users.store": ["/users", "post"],
    "users.show":  ["/users/{user}", "get"]
}
```

### Visibilidade por autenticação

- Usuários **autenticados** recebem todos os modelos e rotas, exceto os listados em `exclude`.
- Usuários **não autenticados** recebem apenas os modelos e rotas listados em `public`.

---

## Comando Artisan

```bash
php artisan luminix:manifest
```

Gera um arquivo JSON com o manifest em `resources/js/config/manifest.json`. Útil quando `boot.includes_manifest` é `false` e o manifest deve ser importado estaticamente no bundle.

**Opções:**

| Opção | Descrição |
|---|---|
| `--no-auth` | Gera um manifest público (apenas com modelos e rotas liberados para visitantes). Salvo como `manifest.public.json` |
| `--path=caminho` | Define o caminho de saída do arquivo manualmente |

```bash
# Manifest completo
php artisan luminix:manifest

# Manifest público
php artisan luminix:manifest --no-auth

# Caminho personalizado
php artisan luminix:manifest --path=public/manifest.json
```

---

## Personalizando os dados de boot

A forma recomendada de personalizar os dados gerados pelo pacote é através de **reducers**. Os reducers são funções registradas estaticamente nos serviços que transformam os dados durante a montagem — antes de serem entregues ao frontend. Eles oferecem mais controle do que o evento `Init`, pois atuam em pontos específicos do pipeline e podem ser removidos programaticamente.

Ambos os serviços (`BootService` e `ManifestService`) usam o trait [`Arandu\Reducible\Reducible`](https://github.com/AranduTech/php-reducible). A assinatura para registrar um reducer é:

```php
ServiceClass::reducer('nomeDoReducer', function ($valor, ...$args) {
    // transforme e retorne o valor
    return $valor;
}, $prioridade); // prioridade padrão: 10 (menor = executa antes)
```

Registre os reducers no método `boot()` de um Service Provider.

---

### Reducers do `BootService`

```php
use Luminix\Frontend\Services\BootService;
```

#### `wireConfig`

Transforma o array completo de boot antes de ser serializado. É o ponto central para adicionar, modificar ou remover qualquer campo dos dados entregues ao frontend.

**Assinatura:** `fn(array $boot): array`

```php
// Adicionar um campo customizado
BootService::reducer('wireConfig', function (array $boot) {
    return [
        ...$boot,
        'app' => [
            ...$boot['app'],
            'version' => config('app.version'),
        ],
    ];
});

// Remover o campo 'auth.csrf' dos dados (ex.: aplicação stateless)
BootService::reducer('wireConfig', function (array $boot) {
    unset($boot['auth']['csrf']);
    return $boot;
});
```

---

### Reducers do `ManifestService`

```php
use Luminix\Frontend\Services\ManifestService;
```

#### `modelManifest`

Aplicado a **todos os modelos** durante a montagem do manifest. Recebe os dados do modelo e a classe do modelo como argumentos. Use para adicionar ou remover campos de todos os modelos de forma uniforme.

**Assinatura:** `fn(array $data, string $modelClass): array`

```php
// Adicionar uma flag customizada a todos os modelos
ManifestService::reducer('modelManifest', function (array $data, string $modelClass) {
    return [
        ...$data,
        'searchable' => in_array(\Laravel\Scout\Searchable::class, class_uses_recursive($modelClass)),
    ];
});

// Remover o campo 'casts' de todos os modelos
ManifestService::reducer('modelManifest', function (array $data) {
    unset($data['casts']);
    return $data;
});
```

#### `model{NomeDoModelo}Manifest`

Aplicado apenas ao **modelo específico** cujo nome de classe base corresponde ao sufixo. Use para personalizar o manifest de um único modelo sem afetar os demais.

**Assinatura:** `fn(array $data): array`

```php
// Adicionar campo apenas ao modelo User (App\Models\User)
ManifestService::reducer('modelUserManifest', function (array $data) {
    return [
        ...$data,
        'avatar_url' => true,
    ];
});

// Adicionar campo apenas ao modelo Product
ManifestService::reducer('modelProductManifest', function (array $data) {
    return [
        ...$data,
        'has_variants' => true,
    ];
});
```

> O nome do reducer é montado como `model` + `class_basename($model)` + `Manifest`. Para `App\Models\BlogPost`, o reducer seria `modelBlogPostManifest`.

---

### Prioridade de execução

Quando múltiplos reducers são registrados para a mesma chave, eles executam em ordem crescente de prioridade (menor valor = executa primeiro). O padrão é `10`.

```php
// Executa primeiro (prioridade 5)
ManifestService::reducer('modelManifest', function (array $data) {
    return [...$data, 'step' => 'primeiro'];
}, 5);

// Executa depois (prioridade 10, padrão)
ManifestService::reducer('modelManifest', function (array $data) {
    return [...$data, 'step' => 'segundo'];
});
```

### Removendo um reducer

`reducer()` retorna uma função que cancela o registro quando chamada:

```php
$unsubscribe = BootService::reducer('wireConfig', fn($boot) => $boot);

// Posteriormente:
$unsubscribe();
```

---

### Evento `Luminix\Frontend\Events\Init`

O evento `Init` é disparado **após** todos os reducers do `BootService` serem aplicados. Prefira os reducers para transformar dados — reserve o evento para integrações que precisam apenas **reagir** ao boot gerado (ex.: logging, auditoria).

```php
use Luminix\Frontend\Events\Init;

Event::listen(Init::class, function (Init $event) {
    Log::info('Boot gerado', ['url' => $event->boot['app']['url']]);
});
```

---

## Estrutura do pacote

```
config/
    frontend.php          Configurações publicáveis

src/
    Commands/
        ManifestCommand.php   Comando php artisan luminix:manifest
    Events/
        Init.php              Evento disparado ao gerar boot data
    Facades/
        Boot.php              Facade para BootService
    Services/
        BootService.php       Monta os dados de boot
        ManifestService.php   Monta o manifest de modelos e rotas
    FrontendServiceProvider.php

views/
    embed.blade.php       Template do elemento de embed
```

---

## Licença

MIT

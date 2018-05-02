# Solarios Permission

A very simple permission package for Laravel 5.6. Includes roles and permissions.

You can install the package via composer:

``` bash
composer require solarios/permission
```

Because Laravel >= 5.5 uses auto-disover, the service provider will automatically register. If you want to include it manually, do so in the `config/app.php` file. 

```php
'providers' => [
    // ...
    Solarios\Permission\PermissionServiceProvider::class,
];
```

## Usage

The packages comes with 2 traits:

- HasPermissions
- HasRoles

### HasPermissions

The `hasPermissionTo()` method checks if the model has a permission. If the model also uses roles, it will also check the role for the permission.

```php
$user->givePermissionTo('manage users');

$user->hasPermissionTo('manage users');
// Returns: true
```

Or when there is a role 'admin' with the 'manage users' permission:

```php
$user->giveRole('admin');
// The admin role has the 'manage users' permission.

$role->hasPermissionTo('manage users');
// Returns: true
```

**Remove a permission**

```php
$user->revokePermissionTo('manage users');
```

### Hasroles

Use this trait to give a model roles.

```php
$user->giveRole('editor');

$user->hasRole('editor');
// Returns: true
```

**Remove a role**

```php
$user->revokeRole('editor');
```

### Relations

Roles and permissions both have a polymorphic relation so we are not bound to one (user) model. 
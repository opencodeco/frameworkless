# Frameworkless

🛁 A truly *clean* project experiment.

## What?

The project must be so clean that anyframework can be plugged without affecting business rules behaviors.


## Business logic

See through: [Business Layer](business-layer/README.md)

### Framework Implementation Suggestions

* Hyperf
* Laravel/Eloquent
* Symfony/DoctrineORM
* Symfony/DoctrineODM
* Slim
* PurePHP/PDO

Main idea is having the framework implementations in another part of the project, such as:

```shell
/business-layer
/laravel
/symfony
/hyperf
/...
```

Having also Dockefiles to each implementation separately.

All projects must respect the business logic inside `/business-layer`

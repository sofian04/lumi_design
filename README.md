# Instructions de Création d'un Projet Symfony "lumi-design"

## Étape 1 : Créer un Projet Symfony (version 7.1 ou supérieure)

1. **Installation de Symfony** : Assurez-vous d'avoir Symfony CLI installé. Si ce n'est pas le cas, suivez [cette documentation](https://symfony.com/download) pour l'installer.

2. **Création du Projet** : Utilisez la commande suivante pour créer un nouveau projet Symfony appelé `lumi-design` :
    ```bash
    symfony new lumi-design --version="7.1.*" --webapp
    ```

## Étape 2 : Créer une Base de Données

1. **Configuration de la Base de Données** : Ouvrez le fichier `.env` et configurez la variable `DATABASE_URL` pour qu'elle pointe vers votre base de données. Par exemple :

    ```env
    DATABASE_URL="mysql://username:password@127.0.0.1:3306/lumi_design"
    ```

2. **Création de la Base de Données** : Utilisez la commande suivante pour créer la base de données :
    ```bash
    php bin/console doctrine:database:create
    ```

## Étape 3 : Créer l'Entité `Product`

1. **Génération de l'Entité** : Utilisez le make:entity pour créer l'entité `Product` :

    ```bash
    php bin/console make:entity Product
    ```

2. **Définition des Champs** : Ajoutez les champs suivants à l'entité `Product` :

    ```php
    // src/Entity/Product.php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: ProductRepository::class)]
    #[UniqueEntity(fields: ['name'])]
    class Product
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 255)]
        private ?string $name = null;

        #[ORM\Column(length: 255)]
        private ?string $slug = null;

        #[ORM\Column(type: Types::TEXT, nullable: true)]
        private ?string $description = null;

        #[ORM\Column(length: 255, nullable: true)]
        private ?string $image = null;

        #[ORM\Column(type: Types::DECIMAL, precision: 7, scale: 2, nullable: true)]
        private ?string $price = null;

        #[ORM\Column]
        private ?int $stock = null;

        #[ORM\Column]
        private ?\DateTimeImmutable $createdAt = null;

        #[ORM\Column(nullable: true)]
        private ?\DateTimeImmutable $updatedAt = null;

        // getters and setters...
    }
    ```

## Étape 4 : Créer l'Entité `Category`

1. **Génération de l'Entité** : Utilisez le make:entity pour créer l'entité `Category` :

    ```bash
    php bin/console make:entity Category
    ```

2. **Définition des Champs** : Ajoutez les champs suivants à l'entité `Category` :

    ```php
    // src/Entity/Category.php

    namespace App\Entity;

    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: CategoryRepository::class)]
    #[UniqueEntity(fields: ['name'])]
    class Category
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(length: 255, unique: true)]
        private ?string $name = null;

        // getters and setters...
    }
    ```

## Étape 5 : Mise à Jour de l'Entité `Product`

1. **Ajout du Champ `category`** : Modifiez l'entité `Product` pour ajouter une relation ManyToOne vers `Category` :

    ```php
    // src/Entity/Product.php

    // ...other use statements
    use App\Entity\Category;

    #[ORM\Entity(repositoryClass: ProductRepository::class)]
    class Product
    {
        // ...other properties

        #[ORM\ManyToOne(inversedBy: 'products')]
        private ?Category $category = null;

        // getters and setters...
    }
    ```

## Étape 6 : Migration de la Base de Données

1. **Génération des Fichiers de Migration** : Utilisez la commande suivante pour générer les fichiers de migration :

    ```bash
    php bin/console make:migration
    ```

2. **Exécution de la Migration** : Appliquez les migrations à la base de données :
    ```bash
    php bin/console doctrine:migrations:migrate
    ```

# Instructions pour le Projet Symfony "lumi-design"

## Étape 1 : Créer un Controller

1. Créer un controller `HomeController` dans le dossier `Front` avec une méthode `index` pour afficher tous les produits :
    ```bash
    php bin/console make:controller Front/HomeController
    ```

## Étape 2 : Afficher la Vue avec Tous les Produits

1. Modifier `HomeController` pour afficher une vue avec tous les produits.

2. Créer une vue `index.html.twig` pour afficher la liste des produits.

## Étape 3 : Créer le Template `base.html.twig`

1. Créer un fichier `base.html.twig` pour définir la structure de base du site.

## Étape 4 : Créer des Partials

1. Créer des fichiers de partials pour les sections réutilisables de votre site (par exemple, header, footer).

## Étape 5 : Installer les Fixtures

1. Installer les fixtures via Composer :
    ```bash
    composer require --dev orm-fixtures
    ```

## Étape 6 : Créer les Fixtures

1. Créer des fixtures pour l'entité `Category`.
2. Créer des fixtures pour l'entité `Product` avec une dépendance sur `Category`.
3. Utiliser des références pour lier les produits aux catégories dans les fixtures.

## Etape 7 : Créer la date de création et de mise à jour automatiquement

1. Ajouter une annotation `HasLifecycleCallbacks` à l'entité `Product`.
2. Ajouter des méthodes `prePersist` et `preUpdate` pour définir les dates de création et de mise à jour.

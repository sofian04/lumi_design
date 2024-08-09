# Créer une commande en base de données dans Symfony 7

## Objectif

Lorsqu'on clique sur le bouton "Commander", sauvegarder les informations de la commande en base de données.

## Étapes

1. Créer une entité `Orders` et `OrderItem` si ce n'est pas déjà fait

2. Dans le contrôleur approprié, créer une méthode pour gérer l'action du bouton "Commander"

3. Dans cette méthode :

    - Créer une nouvelle instance de `Order`
    - Remplir l'objet `Order` avec les données nécessaires
    - Utiliser l'EntityManager pour persister l'objet
    - Appeler la méthode `flush()` pour sauvegarder en base de données

4. Cette route doit être protégée (vérifier que l'utilisateur est connecté)

5. Mettre à jour le template Twig pour inclure le bouton "Commander" avec le bon lien vers la nouvelle action

6. Tester le bouton "Commander" pour vérifier que les données sont bien sauvegardées en base de données

7. Rediriger l'utilisateur vers une page de paiement de la commande qui affiche le detail de la commande

8. Félicitations, vous avez créé une commande en base de données dans Symfony 7 !

## Exemple de code

```php
$order = new Order();
$order->setCreatedAt(new \DateTime());

$cart = $session->get('cart', []);

foreach ($cart as $id => $quantity) {
    $orderItem = new OrderItem();
    $product = $repository->find($id);

    $orderItem->setQuantity($quantity);
    $orderItem->setProduct($product);

    $order->addOrderItem($orderItem);
}

$entityManager->persist($order);
$entityManager->flush();
```

Pour la propriété OrderNumber dans l'entité Order, je veux que ce soit un numéro de commande généré automatiquement.

donc au moment du prepersist que Un OrderListener génère un numéro de commande unique pour chaque commande.


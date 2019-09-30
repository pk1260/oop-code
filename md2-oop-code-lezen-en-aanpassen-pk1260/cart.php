<?php
require 'classes/Product.php';
require 'classes/ProductCatalogue.php';
require 'classes/ShoppingCart.php';

session_start();

/**
 * Als er nog geen winkelwagen is opgeslagen in de sessie
 * dan wordt hij hier aangemaakt en in de sessie opgeslagen
 */
if (empty($_SESSION['cart'])) {
    $_SESSION['cart'] = new ShoppingCart();
}

$productCatalogue = new ProductCatalogue('products.json');
$shoppingCart = $_SESSION['cart'];

if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'add_product':
            $product_code = $_GET['code'];
            $product = $productCatalogue->getProduct($product_code);
            $shoppingCart->addProduct($product);
            header('Location: cart.php');
            break;
        case 'remove_item':
            $item_index = $_GET['item_index'];
            $shoppingCart->removeItem($item_index);
            header('Location: cart.php');
            break;
        case 'remove_all':
            $item_index = $_GET['item_index'];
            $shoppingCart->removeAll($item_index);
            header('Location: cart.php');
            break;
    }
}

$totalPrice = 0;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Winkelwagen</title>
    <link href="https://fonts.googleapis.com/css?family=Oswald:400,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<section class="webshop">
    <h2 class="webshop__title">My first webshop <a href="cart.php" class="cart-icon">Winkelmandje</a></h2>
    <div class="webshop__content">
        <div class="shopping-cart">
            <h2>Winkelwagen</h2>

            <?php if ($shoppingCart->hasProducts()): ?>

                <p>Dit zit er nu in je winkelwagen:</p>

                <?php foreach ($shoppingCart->getProducts() as $index => $product): ?>
                    <div class="shopping-cart__item">
                        <h4><?php echo $product->getTitle() ?> </h4>
                        <img src="<?php echo $product->getImage() ?>" alt="img">
                        <span class="price">$<?php echo $product->getPrice() ?></span>
                        <?php $totalPrice += $product->getPrice() ?>
                        <a href="cart.php?action=remove_item&item_index=<?php echo $index?>">verwijderen</a>
                    </div>
                <?php endforeach; ?>
                <strong>Totaalbedrag: $<?php echo $totalPrice ?></strong>
                <a href="cart.php?action=remove_all"><h2>Remove all</h2></a>

            <?php else: ?>

                <p>Je hebt nog niets in je winkelmandje</p>

            <?php endif; ?>
        </div>
    </div>
    <footer>
        <a href="index.php">Naar de producten</a>
    </footer>
</section>
</body>
</html>
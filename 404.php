<?php

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 Not found</title>
</head>
<style>
    body {
        display: flex;
        align-items: center;
        justify-content: center;
        background: #000;
        color: #bcd;
        min-height: 100vh;
        overflow: hidden;
        flex-direction: column;
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
    }

    .homeLink {
        position: absolute;
        top: 0;
        left: 50%;
        transform: translateX(-50%);
        text-transform: uppercase;
        text-decoration: none;
        color: inherit;
        margin: 0.25rem;
        background-color: dodgerblue;
        font-weight: 800;
        border-radius: .5rem;
        padding: 0.5rem 1rem;
    }
</style>

<body>
    <?php if (isset($_GET['url'])) : ?>
        <a href="<?php echo $_GET['url']; ?>" class="homeLink">Go Back</a>
    <?php else : ?>
        <a href="./index.php" class="homeLink">Go Home</a>
    <?php endif; ?>
    <h1>404 Not found</h1>
    <?php if (isset($_GET['msg'])) : ?>
        <p><?php echo $_GET['msg']; ?></p>
    <?php endif; ?>
</body>

</html>
<?php
require_once("../pdo_connect.php");
$deckId = $_GET['deck_id'] ?? null;
if (!$deckId) {
    die("No Deck ID provided.");
}

try {
    $sql = "
        SELECT c.CardName, c.CardManaValue, c.CardRarity, c.CardCurrentPrice 
        FROM Card AS c
        INNER JOIN Includes AS i ON c.CardSetID = i.CardSetID AND c.CardIndex = i.CardIndex
        WHERE i.DeckID = :deckId
    ";

    $stmt = $dbc->prepare($sql);
    $stmt->bindValue(':deckId', $deckId, PDO::PARAM_INT);
    $stmt->execute();
    $cards = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Error fetching deck: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deck Details</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>
    <h1>Deck Details</h1>
    <div class="deck-container">
        <?php if (!empty($cards)): ?>
            <?php foreach ($cards as $card): ?>
                <div class="card">
                    <h2><?php echo htmlspecialchars($card['CardName']); ?></h2>
                    <p>Mana Value: <?php echo htmlspecialchars($card['CardManaValue']); ?></p>
                    <p>Rarity: <?php echo htmlspecialchars($card['CardRarity']); ?></p>
                    <p>Current Price: $<?php echo htmlspecialchars($card['CardCurrentPrice']); ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No cards found in this deck.</p>
        <?php endif; ?>
    </div>
</body>
</html>
<?php
header('Content-Type: application/json');

// Exemple : URL de la musique
$music_url = 'assets/music/Battle.mp3';

// Réponse JSON
echo json_encode([
    'success' => true,
    'music_url' => $music_url
]);

?>
document.getElementById('musicForm').addEventListener('submit', function (e) {
    e.preventDefault(); // Empêche le rechargement de la page

    // Requête AJAX
    fetch('playMusic.php', {
        method: 'POST'
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const audioPlayer = document.getElementById('audioPlayer');
                const audioSource = document.getElementById('audioSource');
                audioSource.src = data.music_url; // URL de la musique reçue
                audioPlayer.style.display = 'block'; // Affiche le lecteur audio
                audioPlayer.load(); // Recharge l'audio
                audioPlayer.play(); // Joue la musique
            } else {
                alert('Erreur : ' + data.message);
            }
        })
        .catch(error => console.error('Erreur AJAX :', error));
});

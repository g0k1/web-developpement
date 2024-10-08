<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérificateur de Disponibilité du Site</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        #status {
            margin-top: 20px;
            font-size: 1.2em;
        }
    </style>
</head>
<body>
    <h1>Vérificateur de Disponibilité du Site</h1>
    <p>Entrez l'URL du site que vous souhaitez vérifier :</p>
    <input type="text" id="url" placeholder="example.com">
    <button onclick="checkSite()">Vérifier</button>
    <div id="status"></div>

    <script>
        async function checkSite() {
            const url = document.getElementById('url').value;
            const statusDiv = document.getElementById('status');
            
            if (!url) {
                statusDiv.textContent = 'Veuillez entrer une URL.';
                return;
            }

            try {
                // Étape 1 : Demander l'ID de vérification
                const checkResponse = await fetch(`https://check-host.net/check-http?host=${url}&max_nodes=3`, {
                    headers: { 'Accept': 'application/json' }
                });
                const checkResult = await checkResponse.json();

                if (checkResult.ok === 1) {
                    const requestId = checkResult.request_id;

                    // Étape 2 : Attendre quelques secondes pour que la vérification soit complétée
                    setTimeout(async () => {
                        try {
                            const resultResponse = await fetch(`https://check-host.net/check-result/${requestId}`, {
                                headers: { 'Accept': 'application/json' }
                            });
                            const result = await resultResponse.json();

                            // Analyser le résultat
                            const nodes = result.nodes;
                            let statusText = 'Résultats de la vérification :\n';

                            for (const [node, resultList] of Object.entries(nodes)) {
                                const [statusCode, responseTime, statusMessage, httpStatus] = resultList[0];
                                statusText += `- ${node}: ${statusMessage} (HTTP ${httpStatus}, temps de réponse: ${responseTime}s)\n`;
                            }

                            statusDiv.textContent = statusText;
                        } catch (error) {
                            statusDiv.textContent = 'Erreur lors de la récupération des résultats de vérification.';
                        }
                    }, 5000); // Attendre 5 secondes pour que la vérification soit terminée
                } else {
                    statusDiv.textContent = 'Erreur lors de la demande de vérification.';
                }
            } catch (error) {
                statusDiv.textContent = 'Erreur de connexion au service de vérification.';
            }
        }
    </script>
</body>
</html>

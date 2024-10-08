const express = require('express');
const axios = require('axios');
const app = express();
const port = 3000;

app.get('/check-site', async (req, res) => {
    try {
        const response = await axios.get('https://lunodoors.byh.fr/', { timeout: 5000 });
        res.json({ status: response.status === 200 ? 'online' : 'offline' });
    } catch (error) {
        res.json({ status: 'offline' });
    }
});

app.listen(port, () => {
    console.log(`Server listening at http://localhost:${port}`);
});

const express = require('express');
const { getInfo, getImage } = require('./steamMarketUtils');

const app = express();
const PORT = 8080;

// Middleware to parse JSON bodies
app.use(express.json());

app.get('/get-case-price', async (req, res) => {
  const caseName = req.query.caseName;
  if (!caseName) {
    return res.status(400).json({ error: 'Case name is required' });
  }

  try {
    const game = '730'; // Example app ID for CS:GO
    const currency = '1'; // Example currency (USD)
    const info = await getInfo(game, caseName, currency);

    if (!info) {
      return res.status(404).json({ error: 'Case not found' });
    }

    const imageUrl = await getImage(game, caseName, 240, 240);

    res.json({
      result: {
        price: info.lowest_price || 'N/A',
        medianPrice: info.median_price || 'N/A',
        volume: info.volume || 'N/A',
        photoUrl: imageUrl || 'N/A'
      }
    });
  } catch (error) {
    res.status(500).json({ error: `Failed to fetch case price: ${error.message}` });
  }
});

app.listen(PORT, () => {
  console.log(`Server is running on http://localhost:${PORT}`);
});

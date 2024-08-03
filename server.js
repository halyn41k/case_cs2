const express = require('express');
const steamMarketRoutes = require('./routes/steamMarket');

const app = express();
const port = process.env.PORT || 8080;

app.use('/steam-market', steamMarketRoutes);

app.listen(port, () => {
  console.log(`Server is running on port ${port}`);
});

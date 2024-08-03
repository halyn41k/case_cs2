const puppeteer = require('puppeteer');

async function fetchSteamPrice(caseName) {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();

  // Відкриття сторінки Steam Community Market
  await page.goto(`https://steamcommunity.com/market/search?q=${caseName}`);

  // Очікування завантаження необхідних елементів
  await page.waitForSelector('.market_listing_row');

  // Отримання ціни кейсу
  const price = await page.evaluate(() => {
    const item = document.querySelector('.market_listing_row');
    const priceElement = item.querySelector('.normal_price');
    return priceElement ? priceElement.innerText : null;
  });

  await browser.close();

  if (!price) {
    throw new Error('Case not found');
  }

  return {
    name: caseName,
    price
  };
}

fetchSteamPrice('caseName').then(console.log).catch(console.error);

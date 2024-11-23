<template>
  <div>
    <h2>Додати кейс</h2>
    <form @submit.prevent="addCase">
      <div>
        <label for="case_name">Назва кейсу:</label>
        <select v-model="case_name" @change="handleCaseChange" required>
          <option value="Футляр «Сновидіння та кошмари»">Футляр «Сновидіння та кошмари»</option>
          <option value="Футляр «Кіловат»">Футляр «Кіловат»</option>
          <option value="Футляр «Революція»">Футляр «Революція»</option>
          <option value="Футляр «Перелом»">Футляр «Перелом»</option>
          <option value="Футляр «Віддача»">Футляр «Віддача»</option>
          <option value="Футляр «Укус змії»">Футляр «Укус змії»</option>
          <option value="Футляр «Хрома»">Футляр «Хрома»</option>
          <option value="Футляр «Гамма»">Футляр «Гамма»</option>
          <option value="Футляр «Спектр»">Футляр «Спектр»</option>
          <option value="Футляр «Clutch»">Футляр «Clutch»</option>
          <option value="Футляр «Галерея»">Футляр «Галерея»</option>
        </select>
      </div>
      
      <div>
        <label for="account">Акаунт:</label>
        <select v-model="account_id" required>
          <option v-for="account in accounts" :key="account.id" :value="account.id">{{ account.name }}</option>
        </select>
      </div>

      <div>
        <label for="photo">Фото:</label>
        <img v-if="photo" :src="photo" alt="Case Photo" />
      </div>

      <div>
        <label for="price">Ціна за одиницю:</label>
        <input type="number" v-model.number="price" step="0.01" disabled />
        <p v-if="caseLink">
          <a :href="caseLink" target="_blank">Актуальна ціна</a>
        </p>
      </div>

      <div>
        <label for="quantity">Кількість:</label>
        <input type="number" v-model.number="quantity" min="1" required />
      </div>

      <div>
        <label for="total_price">Загальна ціна:</label>
        <input type="number" :value="totalPrice" disabled />
      </div>

      <button type="submit">Додати кейс</button>
    </form>
    <p v-if="error">{{ error }}</p>
  </div>
</template>

<script>
export default {
  data() {
    return {
      case_name: '',
      price: null,
      quantity: 1,
      account_id: null,
      photo: null,
      accounts: [],
      error: null
    };
  },
  computed: {
    caseLink() {
      const caseNames = {
        'Футляр «Сновидіння та кошмари»': 'Dreams & Nightmares Case',
        'Футляр «Кіловат»': 'Kilowatt Case',
        'Футляр «Революція»': 'Revolution Case',
        'Футляр «Перелом»': 'Fracture Case',
        'Футляр «Віддача»': 'Recoil Case',
        'Футляр «Укус змії»': 'Snakebite Case',
        'Футляр «Хрома»': 'Chroma Case',
        'Футляр «Гамма»': 'Gamma Case',
        'Футляр «Спектр»': 'Spectrum Case',
        'Футляр «Clutch»': 'Clutch Case',
        'Футляр «Галерея»': 'Operation Bravo Case'
      };
      const marketName = caseNames[this.case_name];
      if (marketName) {
        return `https://steamcommunity.com/market/listings/730/${encodeURIComponent(marketName)}`;
      }
      return null;
    },
    totalPrice() {
      return (this.price * this.quantity).toFixed(2);
    }
  },
  methods: {
    async addCase() {
      try {
        const response = await fetch('http://cases2.ct.ws/api.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({
            account_id: this.account_id,
            case_name: this.case_name,
            price: this.price,
            quantity: this.quantity,
            total_price: this.totalPrice  // Відправка total_price на сервер
          })
        });
        const data = await response.json();
        if (data.success) {
          alert('Кейс успішно додано!');
        } else {
          this.error = data.error || 'Помилка додавання кейсу';
        }
      } catch (error) {
        this.error = `Помилка запиту: ${error.message}`;
      }
    },
    async fetchAccounts() {
      try {
        const response = await fetch('http://cases2.ct.ws/api.php?fetch=accounts');
        const data = await response.json();
        if (data.success) {
          this.accounts = data.accounts;
        } else {
          this.error = 'Не вдалося завантажити акаунти';
        }
      } catch (error) {
        this.error = `Помилка завантаження акаунтів: ${error.message}`;
      }
    },
    async fetchPrice(caseName) {
      const caseNames = {
        'Футляр «Сновидіння та кошмари»': 'Dreams & Nightmares Case',
        'Футляр «Кіловат»': 'Kilowatt Case',
        'Футляр «Революція»': 'Revolution Case',
        'Футляр «Перелом»': 'Fracture Case',
        'Футляр «Віддача»': 'Recoil Case',
        'Футляр «Укус змії»': 'Snakebite Case',
        'Футляр «Хрома»': 'Chroma Case',
        'Футляр «Гамма»': 'Gamma Case',
        'Футляр «Спектр»': 'Spectrum Case',
        'Футляр «Clutch»': 'Clutch Case',
        'Футляр «Галерея»': 'Operation Bravo Case'
      };
      const marketName = caseNames[caseName];
      if (!marketName) return;

      try {
        const response = await fetch(
          `http://cases2.ct.ws/api_price.php?market_hash_name=${encodeURIComponent(marketName)}`
        );
        const data = await response.json();

        if (data.success === false || !data.median_price) {
          this.price = 'N/A';
          this.error = 'Ціна недоступна для обраного кейсу';
        } else {
          let cleanedPrice = data.median_price.replace(/[^\d,]/g, '').replace(',', '.');
          this.price = parseFloat(cleanedPrice);
          this.error = null;
        }
      } catch (error) {
        this.price = null;
        this.error = `Не вдалося отримати ціну: ${error.message}`;
      }
    },
    handleCaseChange() {
      this.updatePhoto();
      this.fetchPrice(this.case_name);
    },
    updatePhoto() {
      switch (this.case_name) {
        case 'Футляр «Сновидіння та кошмари»':
          this.photo = 'case-cs2/src/assets/grozy.png';
          break;
        case 'Футляр «Кіловат»':
          this.photo = 'case-cs2/src/assets/kilovat.png';
          break;
        case 'Футляр «Революція»':
          this.photo = 'case-cs2/src/assets/revolution.png';
          break;
        case 'Футляр «Перелом»':
          this.photo = 'case-cs2/src/assets/perelom.png';
          break;
        case 'Футляр «Віддача»':
          this.photo = 'case-cs2/src/assets/viddacha.png';
          break;
        case 'Футляр «Укус змії»':
          this.photo = 'case-cs2/src/assets/kyskys.png';
          break;
        case 'Футляр «Хрома»':
          this.photo = 'case-cs2/src/assets/chroma.png';
          break;
        case 'Футляр «Гамма»':
          this.photo = 'case-cs2/src/assets/gamma.png';
          break;
        case 'Футляр «Спектр»':
          this.photo = 'case-cs2/src/assets/spectrum.png';
          break;
        case 'Футляр «Clutch»':
          this.photo = 'case-cs2/src/assets/clutch.png';
          break;
        case 'Футляр «Галерея»':
          this.photo = 'case-cs2/src/assets/gallery.png';
          break;
        default:
          this.photo = null;
      }
    }
  },
  created() {
    this.fetchAccounts();
  }
};
</script>

<style scoped>
form {
  display: flex;
  flex-direction: column;
}

div {
  margin-bottom: 10px;
}

label {
  margin-bottom: 5px;
  font-weight: bold;
}

select,
input[type="number"] {
  width: 100%;
  max-width: 300px;
  padding: 8px;
  border: 1px solid #ccc;
  border-radius: 4px;
  margin-top: 5px;
  box-sizing: border-box;
}

button {
  font-family: inherit;
  padding: 10px 20px;
  border: none;
  color: #fff;
  cursor: pointer;
  border-radius: 4px;
}

button:hover {
  background-color: #FF9918;
}

a {
  color: #FF9918;
  text-decoration: none;
}

a:hover {
  text-decoration: underline;
}

img {
  max-width: 100px;
  margin-top: 10px;
}
</style>

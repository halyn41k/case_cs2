<template>
  <div>
    <h2>Додати кейс</h2>
    <form @submit.prevent="addCase">
      <div>
        <label for="case_name">Назва кейсу:</label>
        <select v-model="case_name" @change="updatePhoto" required>
          <option value="Футляр «Сновидіння та кошмари»">Футляр «Сновидіння та кошмари»</option>
          <option value="Футляр «Кіловат»">Футляр «Кіловат»</option>
          <option value="Футляр «Революція»">Футляр «Революція»</option>
          <option value="Футляр «Перелом»">Футляр «Перелом»</option>
          <option value="Футляр «Віддача»">Футляр «Віддача»</option>
          <option value="Футляр «Укус змії»">Футляр «Укус змії»</option>
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
        <label for="price">Ціна:</label>
        <input type="number" v-model.number="price" step="0.01" required />
        <p v-if="caseLink">
          <a :href="caseLink" target="_blank">Актуальна ціна</a>
        </p>
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
      price: 0.00,
      account_id: null,
      photo: null,
      accounts: [],
      error: null
    };
  },
  computed: {
    caseLink() {
      switch (this.case_name) {
        case 'Футляр «Сновидіння та кошмари»':
          return 'https://steamcommunity.com/market/priceoverview/?market_hash_name=Dreams%20%26%20Nightmares%20Case&appid=730&currency=18';
        case 'Футляр «Кіловат»':
          return 'https://steamcommunity.com/market/priceoverview/?market_hash_name=Kilowatt%20Case&appid=730&currency=18';
        case 'Футляр «Революція»':
          return 'https://steamcommunity.com/market/priceoverview/?market_hash_name=Revolution%20Case&appid=730&currency=18';
        case 'Футляр «Перелом»':
          return 'https://steamcommunity.com/market/priceoverview/?market_hash_name=Fracture%20Case&appid=730&currency=18';
        case 'Футляр «Віддача»':
          return 'https://steamcommunity.com/market/priceoverview/?market_hash_name=Recoil%20Case&appid=730&currency=18';
        default:
          return null;
      }
    }
  },
  created() {
    this.fetchAccounts();
  },
  methods: {
    fetchAccounts() {
      fetch('http://localhost/case-cs2/backend/server.php?fetch=accounts')
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            this.accounts = data.accounts;
          }
        })
        .catch(error => {
          this.error = `Failed to fetch accounts: ${error.message}`;
        });
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
        default:
          this.photo = null;
      }
    },
    addCase() {
      const formData = new FormData();
      const data = {
        account_id: this.account_id,
        case_name: this.case_name,
        price: this.price
      };
      formData.append('data', JSON.stringify(data));

      fetch('http://localhost/case-cs2/backend/server.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          this.$emit('case-added');
          this.case_name = '';
          this.price = 0.00;
          this.account_id = null;
          this.photo = null;
        } else {
          this.error = data.error;
        }
      })
      .catch(error => {
        this.error = `Failed to add case: ${error.message}`;
      });
    }
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
  font-family: inherit; /* Ensure button uses the same font as the text */
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

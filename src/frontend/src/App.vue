<script setup lang="ts">
import { ref, onMounted  } from "vue";
import { RouterLink, RouterView } from 'vue-router';
import { store, updateGesamtsumme, isloggedIn, updateIsloggedIn } from '@/store';

let gesamtPreis = () => {
  return store.value.warenkorb.reduce((total, item) => {
    return total + parseFloat(item.price_brutto) * item.quantity;
  }, 0).toFixed(2);
};

updateGesamtsumme();

let logOutAlert = () => {
  alert('Logout erfolgreich');
};

interface KatalogItem {
  id: number;
  image: string;
  title: string;
  author: string;
  publisher: string;
  description: string;
  weight: number;
  price_brutto: string;
  stock: number;
  quantity: number;
}

let katalogItems = ref<Array<KatalogItem>>([]);

onMounted(async () => {
  try {
    let response = await fetch('https://ivm108.informatik.htw-dresden.de/ewa/g08/backend/Katalog_Beleg_Select_All.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
      },
      body: new URLSearchParams({
        id: 'id',
        image: 'image',
        title: 'title',
        author: 'author',
        publisher: 'publisher',
        description: 'description',
        weight: 'weight',
        price_brutto: 'price_brutto',
        stock: 'stock',
      }),
    });

    if (response.ok) {
      let data = await response.json();
      katalogItems.value = data.map((item: KatalogItem) => ({ ...item, quantity: 0 }));
      console.log(data);
    }
    else if(response.status === 404){
      alert('Katalog nicht gefunden');
      console.error('Fehler beim Abrufen des Katalogs: Katalog nicht gefunden');
    } 
    else {
      console.error('Fehler beim Abrufen des Katalogs: Serverfehler');
    }
  } catch (error) {
    console.error('Fehler beim Abrufen des Katalogs:', error);
  }
});

let input = ref("");

function filteredList() {
  return katalogItems.value.filter(item =>
    item.title.toLowerCase().includes(input.value.toLowerCase())
  );
}
</script>

<template>
    <header>
      <nav>
        <div class="grid-container">
<!-- ------------------------------------------------------------------------------------ -->
          <div class="links">
            <div class="brand">
              Buchshop
            </div>
            <div class="nav-container">
              <RouterLink to="/" class="nav-link">Home</RouterLink>
              <RouterLink to="/katalog" class="nav-link">Katalog</RouterLink>
              <RouterLink to="/about" class="nav-link">About</RouterLink>
            </div>        
          </div>
<!-- ------------------------------------------------------------------------------------ -->
          <div class="mitte">
            <input type="text" v-model="input" placeholder="Suche..." >
            <div v-if="input && filteredList().length > 0" >
              <div v-for="book in filteredList()" :key="book.id" >
                <RouterLink :to="{ name: 'buch-detail', params: { title: book.title } }">{{ book.title }}</RouterLink>
              </div>
            </div>
          </div>
<!-- ------------------------------------------------------------------------------------ -->
            <div class="rechts">
              <div class="warenkorb-container">
                  <RouterLink to="/warenkorb" class="nav-link">
                    <img src="../../images/warenkorb.png" class="warenkorb_image"> ({{ gesamtPreis() }}€)
                  </RouterLink>
              </div>
              <div class="button-container">
                <div v-if="isloggedIn === true" class="nav-link" @click="isloggedIn = false; updateIsloggedIn(false), logOutAlert()">
                  <RouterLink to="/Login" class="login-button-text">Logout</RouterLink>
                </div>
                <div v-if="isloggedIn === false" class="nav-link">
                  <RouterLink to="/Login" class="login-button-text">Login</RouterLink>
                </div>
              </div>
            </div>
          </div>
      </nav>
    </header>
    <RouterView :katalogItems="katalogItems"/>
</template>

<style scoped>
.grid-container {
  display: grid;
  grid-template-columns: repeat(3, 33%);
  background-color: #2196F3;
  padding: 10px;
  width: 100%;
}
.links{
  display: flex;
  justify-content: flex-start;
  align-items: center;
  max-width: 33%;
  list-style: none;
}
.mitte input[type="text"]{
  justify-content: center;
  align-items: center;
  position: relative;
  margin-top: 2em;
}
.rechts{
  display: flex;
  justify-content: center;
  align-items: center;
  margin: auto;
  position: absolute;
  right: 0;  
  /* border: 2px solid red; */
}

/* ------------------------------------------------------ */
.login-button-text{
  text-decoration: none;
  color: white;
  font-size: 150%;
}
header {
  display: block; 
  background-color: black;
  color: white;
  position: fixed;
  width: 100%;
  top: 0;
  z-index: 1;
} 
nav{
  padding: 5px;
  font-size: 250%;     
  border: 2px solid white; 
}
.brand {
  font-size: 1.5em;
  margin-right: 30px;
  padding: 0.1em;
  /* border: 2px solid red; */
}
.nav-container {
  flex-grow: 1;
  padding: 0;  
  /* border: red 2px solid; */
}
.nav-link {
  margin: auto;
  margin-right: 1vw;
  text-decoration: none;
  color: white;
}
.nav-link:hover {
  border-bottom: 2px solid #c278ff;
}
.right-top{
  display: flex;
  justify-content: center;
  align-items: center;
  margin: auto;
  position: absolute;
  right: 0;  
  /* border: 2px solid red; */
}
.button-container{
  display: flex;
  justify-content: flex-end;
  margin-right: 15px;
  list-style: none;
}
.warenkorb_image{
  min-width: 15%;
  max-width: 15%;
  position: relative;
  top: 5px;
}
.warenkorb-container{
  right: 5%;
  justify-content: flex-end;
  margin: auto;
  list-style: none;
}

.searchbar-container{
  display: flex;
  position: relative;
  margin: auto;

  width: 25%;
  height: 3vh;

  right: 25%;

  
  border: 2px solid white;
  border-radius: 100px;
  font-size: 1.5vh;

}

.searchbar-input{
  display: flex;
  position: relative;

  margin: auto;

  height: 100%;
  width: 100%;
  border: none;
  outline: none;
  background: rgba(0, 0, 0, 0);

  font-size: 1.5vh;
  text-decoration: none;
  color: white;



}

.search-button{
  display: flex;



  height: 100%;
  margin-right: 1%;
  padding: 1%;

  position: relative;
  transition: transform 0.15s ease;
}

.search-button:hover{
  transform: scale(0.85);
  cursor: pointer;
}

</style>

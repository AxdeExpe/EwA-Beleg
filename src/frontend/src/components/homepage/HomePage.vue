<script setup lang="ts">
import { onMounted, ref } from 'vue';

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
let randomItem = ref<KatalogItem | null>(null);

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

      let random = Math.floor(Math.random() * katalogItems.value.length);
      randomItem.value = katalogItems.value[random];
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
let decodeBase64Image = (base64String: string) => {
  if(base64String){
    let binaryString = atob(base64String);
    let byteArray = new Uint8Array(binaryString.length);
    for (let i = 0; i < binaryString.length; i++) {
      byteArray[i] = binaryString.charCodeAt(i);
    }
    const blob = new Blob([byteArray], { type: 'image/png' });
    return URL.createObjectURL(blob);
  }
};
</script>

// im template jetzt die Daten des buches anzeigen welche die random id hat
<template>
  
  <div class="item-box">
    <div class="welcome">
      <h1 class="welcome-header-1">Willkommen in unserem Buchshop!</h1>
      <h2 class="welcome-header-2">Wir haben {{ katalogItems.length }} Bücher im Angebot</h2>
    </div>
    <div v-if="randomItem !== null" :key="randomItem.toString()" class="random-item" :id="randomItem.toString()">
        <div class="überschrift">Hier sehen sie unser Angebot des Tages</div>
        <h1>Bild</h1>
        <img :src="decodeBase64Image(randomItem.image)" class="image" alt="Product_Image" width="100" height="100">
        <h1>Titel</h1>
        <a>{{ randomItem.title }}</a>
        <h1>Autor</h1>
        <a>{{ randomItem.author }}</a>
        <h1>Verlag</h1>
        <a>{{ randomItem.publisher }}</a>
        <h1>Beschreibung</h1>   
        <a>{{ randomItem.description }}</a>
        <h1>Preis</h1>
        <a>{{ randomItem.price_brutto }}€</a>
        <h1>Gewicht</h1>
        <a>{{ randomItem.weight }}g</a>
        <h1>Lagerbestand</h1>
        <a>{{ randomItem.stock }}</a>
    </div>
  </div>
</template>

<style>
.item-box {
  justify-content: space-between;
  background-color: rgb(0, 80, 133);
  color: white;
  overflow: hidden;
  width: fit-content;
  min-width: 90%;
  max-width: 90%;
  margin: 3% 0 0 5%; /*top right bottom left*/
  padding: 0px 0px 5px;
  position: relative;
  cursor: pointer;
}
.random-item {
  display: grid;
  grid-template-columns: 20% 80%;
  margin: 10% 5% 5% 5%;
  background-color: salmon;
  border: solid red 5px;
}
.überschrift{
  grid-column: 1 / 3;
  text-align: center;
  font-size: calc(2em + 2vmin);
  font-weight: bold;
  text-decoration: underline;
}
.welcome{
  margin: 5% 5% 5% 5%;
  text-align: center;
}
.welcome-header-1{
  font-weight: bold;
  text-decoration: underline;
  font-size: calc(3em + 2vmin);
}
.welcome-header-2{
  font-size: calc(2em + 2vmin);
}
</style>
<script setup lang="ts">
import { ref, onMounted } from "vue";
import { addToWarenkorb } from '@/store';

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
    } else {
      console.error('Fehler beim Abrufen des Katalogs: Serverfehler');
    }
  } catch (error) {
    console.error('Fehler beim Abrufen des Katalogs:', error);
  }
});

let decodeBase64Image = (base64String: string) => {
  let binaryString = atob(base64String);
  let byteArray = new Uint8Array(binaryString.length);
  for (let i = 0; i < binaryString.length; i++) {
    byteArray[i] = binaryString.charCodeAt(i);
  }
  const blob = new Blob([byteArray], { type: 'image/png' });
  return URL.createObjectURL(blob);
};

let doBestellen = (item: KatalogItem) => {
  if (item.stock < item.quantity) {
    alert('Nicht genügend Exemplare auf Lager');
    item.quantity = 0;
    return;
  }
  alert('Es wurden ' + item.quantity + ' Exemplare von ' + item.title + ' bestellt.');
  addToWarenkorb(item);
  item.quantity = 0;
};
</script>

<template>
    <div>
    <div v-for="item in katalogItems" :key="item.id" class="item-box">
      <div class="Image flex_inner">
        <!-- <img :src="item.image" alt="Bild" width="100" height="100"> -->
        <img :src="decodeBase64Image(item.image)" alt="Bild" width="100" height="100">
      </div>
      <div class="titel flex_inner">
        <h1>Titel</h1>
        <a>{{ item.title }}</a>
      </div>
      <div class="author flex_inner">
        <h1>Autor</h1>
        <a>{{ item.author }}</a>
      </div>
      <div class="Verlag flex_inner">
        <h1>Verlag</h1>
        <a>{{ item.publisher }}</a>
      </div>
      <div class="beschreibung flex_inner">
        <h1>Beschreibung</h1>   
        <a>{{ item.description }}</a>
      </div>
      <div class="Preis flex_inner">
        <h1>Preis</h1>
        <a>{{ item.price_brutto }}€</a>
      </div>
      <div class="Gewicht flex_inner">
        <h1>Gewicht</h1>
        <a>{{ item.weight }}g</a>
      </div>
      <div class="Lagerbestand flex_inner">
        <h1>Lagerbestand</h1>
        <a>{{ item.stock }}</a>
      </div>
      <div class="Bestellung flex_inner">
        <h1>Bestellung</h1>
        <div class="menge flex_inner">
          <p> {{ item.quantity }} </p>
        </div>
        <div class="buttons">
          <button class="button" @click="item.quantity++">+</button>
          <button class="button" @click="item.quantity--">-</button>
        </div>
        <div class="buttons">
          <button class="bestellen" @click="doBestellen(item)">In den Warenkorb</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.item-box {
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    background-color: rgb(0, 80, 133);
    color: white;
    max-height: 60%;
    /* width: 75%; */
    margin: 5vh 10vw 10em 10vw; /*top right bottom left*/
    padding: 0;
    position: relative;
    font-size: 1;
    border: 1px solid red;
}
.flex_inner{
    display: flex;
    flex-direction: column;
    max-width: 100%;
}
.Image{
    text-align: center;
    border: 1px solid red;
    /* margin-left: 1em;
    margin-right: 1em; */
    margin: 1em 1em 1em 1em; /*top right bottom left*/

}
.titel{
    text-align: center;
    border: 1px solid red;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
    width: 250px;
}
.author{
    text-align: center;
    border: 1px solid red;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
    width: 170px;
}
.Verlag{
    text-align: center;
    border: 1px solid red;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
    width: 150px;
}
.beschreibung{
    text-align: center;
    border: 1px solid red;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
    width: 500px;
}
.Preis{
    text-align: center;
    border: 1px solid red;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
    width: 100px;
}
.Gewicht{
    text-align: center;
    border: 1px solid red;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
    width: 150px;
}
.Lagerbestand{
    text-align: center;
    border: 1px solid red;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
    width: 200px;
}
.Bestellung{
    text-align: center;
    border: 1px solid red;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
    width: 150px;
}
.menge{
    text-align: center;
    border: 1px solid red;
    margin: 1.5em 0.5em 0.5em 0.5em; /*top right bottom left*/
}
.buttons{
    border: 1px solid red;
    display: flex;
    margin: 1.5em 0.5em 0.5em 0.5em; /*top right bottom left*/
}
.button{
    background-color: rgb(255, 255, 255);
    color: black;
    font-size: 1em;
    margin: 0.5em 0.5em 0.5em 0.5em; /*top right bottom left*/
    padding: 0.25em 0.25em 0.25em 0.25em; /*top right bottom left*/
    flex: 1;
}
.bestellen{
    background-color: rgb(255, 255, 255);
    color: black;
    font-size: 1em;
    margin: 0.5em 0.5em 0.5em 0.5em; /*top right bottom left*/
    padding: 0.25em 0.25em 0.25em 0.25em; /*top right bottom left*/
    flex: 1;
}
</style>
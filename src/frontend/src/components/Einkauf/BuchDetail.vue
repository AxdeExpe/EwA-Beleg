<script setup lang="ts">
import { ref, onMounted, defineProps } from 'vue';
import { useRoute } from 'vue-router';
import { addToWarenkorb } from '@/store';

interface katalogItem {
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

let props = defineProps(['katalogItems']);
let route = useRoute();

let book = ref<katalogItem>({
  id: 0,
  image: '',
  title: '',
  author: '',
  publisher: '',
  description: '',
  weight: 0,
  price_brutto: '',
  stock: 0,
  quantity: 0,
});


onMounted(() => {
  let title = route.params.title;
  let selectedBook = props.katalogItems.find((item: katalogItem) => item.title === title);
  if (selectedBook) {
    book.value = Object.assign({},selectedBook);
  }
});

console.log("Updated Book:", book);


let decodeBase64Image = (base64String: string) => {
  let binaryString = atob(base64String);
  let byteArray = new Uint8Array(binaryString.length);
  for (let i = 0; i < binaryString.length; i++) {
    byteArray[i] = binaryString.charCodeAt(i);
  }
  let blob = new Blob([byteArray], { type: 'image/png' });
  return URL.createObjectURL(blob);
};

let doBestellen = (item: katalogItem) => {
  if(item.quantity <= 0){
    return;
  }

  if (item.stock < item.quantity) {
    alert('Nicht genügend Exemplare auf Lager');
    item.quantity = 0;
    return;
  }

  addToWarenkorb(item);
  item.quantity = 0;
};
</script>

<template>
  <div class="ausgabe">
    <div class="item-beschreibung">Bild</div>
    <img :src="decodeBase64Image(book.image)" alt="Bild" width="100" height="100">
    <div></div>
    <div class="item-beschreibung">Titel</div>
    <div>{{ book.title }}</div>
    <div></div>
    <div class="item-beschreibung">Autor</div>
    <div>{{ book.author }}</div>
    <div></div>
    <div class="item-beschreibung">Verlag</div>
    <div>{{ book.publisher }}</div>
    <div></div>
    <div class="item-beschreibung">Beschreibung</div>   
    <div>{{ book.description }}</div>
    <div class="einkauf">
      <h1>Bestellen?</h1>
      <textarea v-model="book.quantity" type="number"></textarea><br>
      <button class="button-menge" @click="book.quantity++">+</button>
      <button class="button-menge" @click="book.quantity--">-</button><br>
      <button class="bestellen" @click="doBestellen(book)">In den Warenkorb</button>
    </div>
    <div class="item-beschreibung">Preis</div>
    <div>{{ book.price_brutto }}€</div>
    <div></div>
    <div class="item-beschreibung">Gewicht</div>
    <div class="item-g">{{ book.weight }}g</div>
    <div></div>
    <div class="item-beschreibung">Lagerbestand</div>
    <div>{{ book.stock }}</div>
  </div>
</template>

<style scoped>
.ausgabe {
  display: grid;
  grid-template-columns: 15% 60% 24%;
  gap: 10px;
  padding: 10px;
  border: 1px solid black;
  border-radius: 5px;
  background-color: aliceblue;
  color: black;
  width: 80%;
  margin: 3% 0 0 10%;
  font-size: calc(.1em + 2vmin);
}
.einkauf{
  border: solid white 5px;
  background-color: lightblue;
}
h1{
  font-size: calc(1em + 2vmin);
  text-decoration: underline;
  margin: 10px 10px 10px 10px;
}
textarea{
  width: 50%;
  height: 15%;
  border-radius: 5px;
  font-size: calc(.1em + 2vmin);
  margin: 10px 10px 10px 10px;
}
.button-menge{
  font-size: calc(.1em + 2vmin);
  width: 15%;
  height: 15%;
  border-radius: 5px;
  color: white;
  background-color: yellowgreen;
  margin: 10px 10px 10px 10px;
}
.bestellen{
  font-size: calc(.1em + 2vmin);
  width: 50%;
  height: 15%;
  border-radius: 5px;
  color: white;
  background-color: yellowgreen;
  margin: 10px 10px 10px 10px;
}
.item-beschreibung{
  font-size: calc(.5em + 2vmin);
  font-weight: bold;
  margin: 10px 10px 10px 10px;
}
</style>
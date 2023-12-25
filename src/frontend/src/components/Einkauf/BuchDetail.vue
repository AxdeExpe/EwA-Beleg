<script setup lang="ts">
import { ref, onMounted, defineProps } from 'vue';
import { useRoute } from 'vue-router';

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
</script>

<template>
    <div class="ausgabe">
      <div class="Image_container flex_inner">
        <img :src="decodeBase64Image(book.image)" class="image" alt="Bild" width="100" height="100">
      </div>
      <div class="titel flex_inner">
        <h1>Titel</h1>
        <p>{{ book.title }}</p>
      </div>
      <div class="author flex_inner">
        <h1>Autor</h1>
        <a>{{ book.author }}</a>
      </div>
      <div class="Verlag flex_inner">
        <h1>Verlag</h1>
        <a>{{ book.publisher }}</a>
      </div>
      <div class="beschreibung flex_inner">
        <h1>Beschreibung</h1>   
        <a>{{ book.description }}</a>
      </div>
      <div class="Preis flex_inner">
        <h1>Preis</h1>
        <a>{{ book.price_brutto }}€</a>
      </div>
      <div class="Gewicht flex_inner">
        <h1>Gewicht</h1>
        <a>{{ book.weight }}g</a>
      </div>
      <div class="Lagerbestand flex_inner">
        <h1>Lagerbestand</h1>
        <a>{{ book.stock }}</a>
      </div>
  </div>
</template>

<style scoped>
.ausgabe {
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-template-rows: 1fr 1fr 1fr 1fr 1fr 1fr 1fr;
  grid-template-areas: 
    "Image_container titel"
    "Image_container author"
    "Image_container Verlag"
    "Image_container beschreibung"
    "Image_container Preis"
    "Image_container Gewicht"
    "Image_container Lagerbestand";
  grid-gap: 10px;
  padding: 10px;
  margin-top: 10%;
  border: 1px solid black;
  border-radius: 5px;
  background-color: aliceblue;
  color: black;
}
</style>
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

let doBestellen = (item: KatalogItem) => {

  if(item.quantity <= 0){
    return;
  }

  if (item.stock < item.quantity) {
    alert('Nicht genügend Exemplare auf Lager');
    item.quantity = 0;
    return;
  }
  alert('Es wurden ' + item.quantity + ' Exemplare von ' + item.title + ' bestellt.');
  addToWarenkorb(item);
  item.quantity = 0;
};

let increaseQuantity = (item: KatalogItem) => {
  item.quantity++;
};

let decreaseQuantity = (item: KatalogItem) => {

  if(item.quantity <= 0){
    return;
  }

  item.quantity--;
};

let resizeProduct = (item: KatalogItem, index: number) => {

  let container = document.getElementById(item.id.toString());

  if(container === null){
    return;
  }

  let isOverflowing = container.scrollHeight > container.offsetHeight;

  if (isOverflowing) {
    container.style.maxHeight = 'fit-content';
  } else {
    container.style.maxHeight = '300px';
  }
};

</script>

<template>
    <div>
     <div v-for="(item, index) in katalogItems" :key="item.id" class="item-box" :id="item.id.toString()" @click="resizeProduct(item, index)">
      <div class="Image_container flex_inner">
        <img :src="decodeBase64Image(item.image)" class="image" alt="Product_Image" width="100" height="100">
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
          <textarea v-model="item.quantity" type="number"></textarea>
        </div>
        <!-- <div class="buttons">
          <button class="button" @click="increaseQuantity(item)">+</button>
          <button class="button" @click="decreaseQuantity(item)">-</button>
        </div> -->
        <div class="buttons">
          <button class="bestellen" @click="doBestellen(item)">In den Warenkorb</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>

h1{
    font-size: 2vh;
    text-decoration: underline;
    margin: auto;
}
a{
    font-size: 1.5vh;
    text-decoration: none;
    margin: auto;
}
.item-box {
    display: flex;
    justify-content: space-between;
    background-color: rgb(0, 80, 133);
    color: white;
    overflow: hidden;
    width: fit-content;
    min-width: 90%;
    max-width: 90%;
    margin: 15px 0 0 5%; /*top right bottom left*/
    max-height: 300px;
    padding: 0px 0px 5px;
    position: relative;
    cursor: pointer;
}
.flex_inner{
    flex-direction: column;
    max-width: 100%;
}
/* ----------------------------------------------------------------------------------------- */
.Image_container {
    justify-content: center;
    margin: 1em -3em 1em 1em; /*top right bottom left*/
    position: relative; 
    width: 10%;
}
.image {
    width: 100%;
    height: 100%;
    object-fit: contain;
    position: absolute;

    top: 0;
    left: 0;
}
.titel{
    text-align: center;
    margin: 1em -3em 1em -3em; /*top right bottom left*/
    width: fit-content;
    width: 9%;
    word-wrap: break-word;
}
.author{
    text-align: center;
    margin: 1em -3em 1em -3em; /*top right bottom left*/
    width: fit-content;
    width: 9%;
    word-wrap: break-word;
}
.Verlag{
    text-align: center;
    margin: 1em -3em 1em -3em; /*top right bottom left*/
    width: fit-content;
    width: 9%;
    word-wrap: break-word;
}
.beschreibung{
    text-align: center;
    margin: 1em -3em 1em -3em; /*top right bottom left*/
    width: fit-content;
    width: 25%;
    word-wrap: break-word;
}
.Preis{
    text-align: center;
    margin: 1em -3em 1em -3em; /*top right bottom left*/
    width: fit-content;
    width: 7%;
    word-wrap: break-word;
}
.Gewicht{
    text-align: center;
    margin: 1em -3em 1em -3em; /*top right bottom left*/
    width: fit-content;
    width: 7%;
    word-wrap: break-word;
}
.Lagerbestand{
    text-align: center;
    margin: 1em -3em 1em -3em; /*top right bottom left*/
    width: fit-content;
    width: 7%;
    word-wrap: break-word;
/* border: 1px solid red; */
}
.Bestellung{
    text-align: center;
    margin: 1em 0.5em 1em -3em; /*top right bottom left*/
    width: fit-content;
    width: 7%;
    word-wrap: break-word;
/* border: 1px solid red; */
}
/* ------------------------------------------------------------------------------------ */
.menge{
    text-align: center;
    margin: 1.5em 0.5em 0.5em 0.5em; /*top right bottom left*/
}
.buttons{
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
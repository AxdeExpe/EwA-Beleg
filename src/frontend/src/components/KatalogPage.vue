<script setup lang="ts">
import { ref, onMounted } from "vue";

interface KatalogItem {
  id: number;
  image: string;
  title: string;
  author: string;
  publisher: string;
  description: string;
  weight: number;
  price: number;
  stock: number;
}

let katalogItems = ref<Array<KatalogItem>>([]);
let count = ref(0);

// let katalogItems = ref([]);
// let count = ref(0);

onMounted(async () => {
  try {
    let response = await fetch('http://ivm108.informatik.htw-dresden.de/ewa/g08/backend/Katalog_Beleg_Select_All.php', {
    //let response = await fetch('../../../../backend/Katalog_Beleg_Select_All.php', {
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
        price: 'price',
        stock: 'stock',
      }),
    });

    if (response.ok) {
      let data = await response.json();
      katalogItems.value = data;
      console.log(data);
    } else {
      console.error('Fehler beim Abrufen des Katalogs: Serverfehler');
    }
  } catch (error) {
    console.error('Fehler beim Abrufen des Katalogs:', error);
  }
});
</script>

<template>
    <div>
    <div v-for="item in katalogItems" :key="item.id" class="item-box">
      <div class="Image">
        <img :src="item.image" alt="Bild" width="100" height="100">
      </div>
      <div class="titel">
        <h1>Titel</h1>
        <a>{{ item.title }}</a>
      </div>
      <div class="author">
        <h1>Autor</h1>
        <a>{{ item.author }}</a>
      </div>
      <div class="Verlag">
        <h1>Verlag</h1>
        <a>{{ item.publisher }}</a>
      </div>
      <div class="beschreibung">
        <h1>Beschreibung</h1>   
        <a>{{ item.description }}</a>
      </div>
      <div class="Preis">
        <h1>Preis</h1>
        <a>{{ item.price }}€</a>
      </div>
      <div class="Gewicht">
        <h1>Gewicht</h1>
        <a>{{ item.weight }}Kg</a>
      </div>
      <div class="Lagerbestand">
        <h1>Lagerbestand</h1>
        <a>{{ item.stock }}</a>
      </div>
      <div class="Bestellung">
        <h1>Bestellung</h1>
        <div class="menge">
          <p> {{ count }}</p>
        </div>
        <div class="buttons">
          <button class="button" @click="count++">+</button>
          <button class="button" @click="count--">-</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.item-box {
    display: flex;
    /* justify-content: space-around; */
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
    max-width: 20%;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
}
.beschreibung{
    text-align: center;
    border: 1px solid red;
    max-width: 25%;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
}
.author{
    text-align: center;
    border: 1px solid red;
    max-width: 25%;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
}
.Verlag{
    text-align: center;
    border: 1px solid red;
    max-width: 25%;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
}
.Preis{
    text-align: center;
    border: 1px solid red;
    max-width: 25%;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
}
.Gewicht{
    text-align: center;
    border: 1px solid red;
    max-width: 25%;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
}
.Lagerbestand{
    text-align: center;
    border: 1px solid red;
    max-width: 25%;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
}
.Bestellung{
    text-align: center;
    border: 1px solid red;
    max-width: 25%;
    margin: 1em 1em 1em 1em; /*top right bottom left*/
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
</style>
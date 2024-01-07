<script setup lang="ts">
import AdminBereichBox from '@/components/AdminBereich/AdminBereichBox.vue'
import { password, username } from '@/store';
import { ref, onMounted } from "vue";

interface KatalogItem {
  id: number;
  image: string;
  title: string;
  author: string;
  publisher: string;
  description: string;
  weight: number;
  price_netto: string;
  stock: number;
  quantity: number;
  mwst: number;
}

let login = ref({
  username: '',
  password: '', 
})

let katalogItems = ref<Array<KatalogItem>>([]);

let getKatalog = async () => {
  try {
    login.value.username = username;
    login.value.password = password;
    let response = await fetch('https://ivm108.informatik.htw-dresden.de/ewa/g08/backend/Admin_Bestellungen_Select_All.php', {
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
        price_netto: 'price_netto',
        stock: 'stock',
        mwst: 'mwst',
        username: login.value.username,
        password: login.value.password,
      }),
    });
    if (response.status === 200) {
      let data = await response.json();
      katalogItems.value = data['Stock'].map((item: KatalogItem) => ({ ...item, quantity: 0 }));
    }
    else if (response.status === 400){
      console.error('data is invalid, no POST-Request');
    }
    else if (response.status === 404){
      console.error('Fehler beim Abrufen des Katalogs: Katalog nicht gefunden');
    } 
    else if (response.status === 500){
      console.error('Fehler beim Abrufen des Katalogs: Serverfehler');
    }
  } catch (error) {
    console.error('Fehler beim Abrufen des Katalogs:', error);
  }
}

onMounted(async () => {
  await getKatalog();
});

let deleteItem = async (id: number) => {
  if (window.confirm('Möchten Sie das Buch wirklich löschen?')) {
    try {
      let response = await fetch('https://ivm108.informatik.htw-dresden.de/ewa/g08/backend/admin_delete_books.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
          id: id.toString(),
          username: login.value.username,
          password: login.value.password,
        }),
      });
      if (response.status === 200) {
        alert('Buch wurde erfolgreich gelöscht');
        //reload page / get new data
        await getKatalog();
      }
      else if (response.status === 400){
        console.error('data is invalid, no POST-Request');
      }
      else if (response.status === 404){
        alert('Buch nicht gefunden');
        console.error('Fehler beim Löschen des Buchs: Buch nicht gefunden');
      } 
      else if (response.status === 500){
        console.error('Fehler beim Löschen des Buchs: Serverfehler');
      }
    } catch (error) {
      console.error('Fehler beim Löschen des Buchs:', error);
    }
  }
}
  
</script>

<template>
  <div>
    <AdminBereichBox>
      <template v-slot:Katalogverwaltung>
        <div v-for="(item) in katalogItems" :key="item.id" :id="item.id ? item.id.toString() : ''" class="item-box">
          <div>
            <h1>Bildpfad</h1>
            <textarea v-model="item.image"></textarea>
          </div>
          <div>
            <h1>Titel</h1>
            <textarea v-model="item.title"></textarea>
          </div>
          <div>
            <h1>Autor</h1>
            <textarea v-model="item.author"></textarea>
          </div>
          <div>
            <h1>Verlag</h1>
            <textarea v-model="item.publisher"></textarea>
          </div>
          <div>
            <h1>Beschreibung</h1>   
            <textarea v-model="item.description"></textarea>
          </div>
          <div>
            <h1>Preis</h1>
            <textarea v-model="item.price_netto"></textarea>
          </div>
          <div>
            <h1>Gewicht</h1>
            <textarea v-model="item.weight"></textarea>
          </div>
          <div>
            <h1>Lagerbestand</h1>
            <textarea v-model="item.stock"></textarea>
          </div>
          <div class="delete-button" @click="deleteItem(item.id)">
            X
          </div>
        </div>
      </template>
    </AdminBereichBox>
  </div>
</template>

<style scoped>
.item-box {
    display: grid;
    grid-template-columns: repeat(9,11%);
    grid-template-rows: repeat(2, 100px);
    justify-content: space-between;
    background-color: rgb(0, 80, 133);
    color: white;
    margin: 15px 0 0 0; /*top right bottom left*/
    font-size: calc(.5em + 0.1vw);
    width: 100%;
    padding: 0px 0px 5px;
    position: relative;
    cursor: pointer;
}
.item-box textarea{
  height: 150px;
  width: 100%;
}
.delete-button{
  left: 0;
  top: 50%;
  position: relative;
  background-color: red;
  width: 50%;
  margin: auto;
  color: white;
  text-align: center;
  justify-content: center;
  font-size: calc(2em + 1vw);
  font-weight: bold;
  cursor: pointer;
}
</style>
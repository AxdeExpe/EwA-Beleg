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
let mwst = ref<number>(0);

onMounted(async () => {
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
      //mwst werte von allen katalog items in array speichern
      let mwst_test = [];
      for (let i = 0; i<data['Stock'].length; i++){
        mwst_test.push(data['Stock'][i]['mwst']);
      }
      //prüfen ob alle werte im array gleich sind
      let mwst_test_2 = mwst_test.every((val, i, arr) => val === arr[0]);
      //wenn nicht gleich, dann alert
      if (mwst_test_2 == false){
        alert('Mehrwertsteuer der Artikel unterscheidet sich');
      }
      mwst.value = data['Stock'][0]['mwst'];
    }
    else if (response.status === 400){
      console.error('data is invalid, no POST-Request');
    }
    else if (response.status === 404){
      alert('Katalog nicht gefunden');
      console.error('Fehler beim Abrufen des Katalogs: Katalog nicht gefunden');
    } 
    else if (response.status === 500){
      console.error('Fehler beim Abrufen des Katalogs: Serverfehler');
    }
  } 
  catch (error) {
    console.error('Fehler beim Abrufen des Katalogs:', error);
  }
});

 let submit = async () => {
    //durch alle id durch iterieren und dann alle werte updaten
    login.value.username = username;
    login.value.password = password;
    
    //Auf Submit prüfen ob die mwst geändert wurde, falls ja den mwst wert für alle katalog items ändern
    for(let i = 0; i<katalogItems.value.length; i++){
      if(katalogItems.value[i]['mwst'] != mwst.value){
        katalogItems.value[i]['mwst'] = mwst.value;
      }
    }
  
    for(let i = 0; i<katalogItems.value.length; i++){
      try {
        
        let response = await fetch('https://ivm108.informatik.htw-dresden.de/ewa/g08/backend/admin_update_table_books.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
          },
          body: new URLSearchParams({
            id: katalogItems.value[i]['id'].toString(),
            image: katalogItems.value[i]['image'],
            title: katalogItems.value[i]['title'],
            author: katalogItems.value[i]['author'],
            publisher: katalogItems.value[i]['publisher'],
            description: katalogItems.value[i]['description'],
            weight: katalogItems.value[i]['weight'].toString(),
            price_netto: katalogItems.value[i]['price_netto'], 
            stock: katalogItems.value[i]['stock'].toString(),
            mwst: katalogItems.value[i]['mwst'].toString(), // Verwende den tatsächlichen Wert von mwst
            username: login.value.username,
            password: login.value.password,
          }),
        })
        if (response.status === 200) {
        console.log('Erfolgreich updated!');
        alert('Erfolgreich updated!');
      } else if (response.status === 304){
        console.error('Die Daten sind gleich (unverändert)');
        alert('Die Daten sind gleich (unverändert)');
      } else if (response.status === 400){
        console.error('Keine Post-Request, Datenfelder sind leer oder invalide');
        alert('Keine Post-Request, Datenfelder sind leer oder invalide');
      } else if (response.status === 401){
        console.error('Keine Berechtigung');
        alert('Keine Berechtigung');
      } else if (response.status === 404){
        console.error('Es gibt kein Buch mit dieser ID');
        alert('Es gibt kein Buch mit dieser ID');
      } else if (response.status === 500){
        console.error('Serverfehler');
        alert('Serverfehler');
      } 
   } catch (error: any) {
   console.error('Fehler beim Senden des Formulars:', error.message)
   }
 }
 }
</script>

<template>
  <div>
    <AdminBereichBox>
      <template v-slot:Katalogverwaltung>
        <div class="mwst-box">
          <textarea  v-model="mwst"></textarea>
        </div>
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
        </div>
        <button type="submit" class="submit-button" @click="submit">Submit</button>
      </template>
    </AdminBereichBox>
  </div>
</template>

<style scoped>
.item-box {
  display: grid;
  grid-template-columns: repeat(8, 12%);
  grid-template-rows: repeat(2, 100px);
  justify-content: space-between;
  background-color: rgb(0, 80, 133);
  color: white;
  margin: 15px 0 0 0; /*top right bottom left*/
  padding: 0px 0px 5px;
  position: relative;
  cursor: pointer;
}
.item-box textarea{
  height: 150px;
}
.mwst-box textarea{
  width: 25%;
}
.submit-button{
  font-size: calc(.5em + 1vw);
  text-align: center;
  font-weight: bold;
  width: 25%;
  height: 100%;
  background-color: #4CAF50;
  color: white;
  border-radius: 5px;
  padding: 15px 32px;
  margin-top: calc(.1em + 1vw);    
}
</style>
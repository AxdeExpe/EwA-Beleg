<script setup lang="ts">
import { onMounted } from 'vue';
import { ref } from "vue";
import { store, removeFromWarenkorb, updateGesamtsumme, isloggedIn, username, password } from '@/store';
import router from '@/router';

const warenkorb = store.value.warenkorb;

onMounted(() => {
  updateGesamtsumme();
});

const gesamtsumme = () => {
  return warenkorb.reduce((total, item) => {
    return total + parseFloat(item.price_brutto) * item.quantity;
  }, 0).toFixed(2);
};

let order = async () => {
  console.log(isloggedIn.value);

  if(isloggedIn.value){
    //bestellen
    console.log('bestellen');



    // Dynamicly wrap JSON-Datapacket
    let data = [];


    // TODO get username and password from store, currently it is undefined
    data.push({
      "username": username,
      "password": password
    });

    // get the book id and the amount of books
    for(let i = 0; i < store.value.warenkorb.length; i++){
      data.push({
        "id": store.value.warenkorb[i].id,
        "amount": store.value.warenkorb[i].quantity
      });
    }

    console.log(data);
    return;


    //request zum server und mit stripe bezahlen
    try{
      let response = await fetch('http://ivm108.informatik.htw-dresden.de/ewa/g08/backend/stripe/stripe.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },


        // JSON-Format:
        // [
        //     {"username": "test_user", "password": "test"},
        //     {"id": 1, "amount": 2},
        //     {"id": 2, "amount": 7}
        // ]


        body: JSON.stringify({
          "username": username,
          "password": password,
          "warenkorb": store.value.warenkorb
        })



      });

      if (response.status === 200) {
        let data = await response.json();





      } else if (response.status === 400) {
        console.error('warenkorb is empty or not set, no POST-Request');
        alert('warenkorb is empty or not set, no POST-Request');
      } else if (response.status === 401) {
        console.error('not authorized');
        alert('not authorized');
      } else if(response.status === 404){
        console.error('Any book not found');
        alert('Any book not found');
      } else if(response.status === 409){
        console.error('Not enough books in stock');
        alert('Not enough books in stock');
      } else if (response.status === 500) {
        console.error('Server Error');
        alert('Server Error');
      }
    }
    catch (e) {
      console.log(e);
    }

    }
  else{
    //login
    alert('Um bestellen zu können, müssen Sie sich einloggen!');
    return;
  }

};
</script>

<template>
   <div class="item-box">
    <h1>Warenkorb:</h1>
    <ul class="ausgabe">
      <li v-for="item in warenkorb" :key="item.id">
        <div>{{ item.title }}</div>
        <div>Menge: {{ item.quantity }}</div>
        <div>Einzelpreis: {{ parseFloat(item.price_brutto) }}€</div>
        <div>Teilgesamtpreis: {{ (parseFloat(item.price_brutto) * item.quantity).toFixed(2) }}€</div>
        <button @click="removeFromWarenkorb(item.id)">Entfernen</button>
      </li>
    </ul>
    <div class="endsumme">
      <strong>Gesamtsumme: {{ gesamtsumme() }}€</strong>
    </div>

    <button @click="order()">Bestellen</button>
  </div>
</template>

<style scoped>
.item-box {
    margin: auto;
    margin-top: 10%;
    display: flex;
    flex-direction: column;
    background-color: rgb(0, 80, 133);
    color: white;
    min-height: 40%;
    width: 50%;
    justify-content: center;
    align-items: center;
    position: relative;
    font-size: 1;
    /* border: 1px solid red; */
}

h1{
    font-size: 300%;
    text-decoration: underline;
    margin: auto;
    
}
.ausgabe{
    margin: auto;
    /* border: red 1px solid; */
}
.endsumme{
    margin: auto;
    font-size: 300%;
    text-decoration: underline;
}

</style>
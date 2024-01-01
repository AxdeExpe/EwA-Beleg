<script setup lang="ts">
import { onMounted } from 'vue';
import { ref } from "vue";
import { store, removeFromWarenkorb, updateGesamtsumme, isloggedIn, username, password, is_admin } from '@/store';
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

  // check if warenkorb is empty
  if(store.value.warenkorb.length <= 0){
    alert('Es sind keine Artikel im Warenkorb!');
    return;
  }

  // check if admin
  if(is_admin.value){
    alert('Admins können keine Bestellungen aufgeben!');
    return;
  }

  if(isloggedIn.value){
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

    console.log(JSON.stringify(data));

    // JSON-Format:
    // [
    //     {"username": "test_user", "password": "test"},
    //     {"id": 1, "amount": 2},
    //     {"id": 2, "amount": 7}
    // ]

    //request zum server und mit stripe bezahlen
    try{
      let response = await fetch('https://ivm108.informatik.htw-dresden.de/ewa/g08/backend/stripe/stripe.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        redirect: 'follow',

        body: JSON.stringify(data),

      });

      if (response.ok) {
        let redirectUrl = await response.json();

        console.log(redirectUrl);

        window.location.href = redirectUrl;

      } else if (response.status === 400) {
        console.log('warenkorb is empty or not set, no POST-Request');
        alert('warenkorb is empty or not set, no POST-Request');
      } else if (response.status === 401) {
        console.log('not authorized');
        alert('not authorized');
      } else if(response.status === 404){
        console.log('Any book not found');
        alert('Any book not found');
      } else if(response.status === 409){
        console.log('Not enough books in stock');
        alert('Not enough books in stock');
      } else if (response.status === 500) {
        console.log('Server Error');
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
    <div class="ausgabe">
      <div v-for="item in warenkorb" :key="item.id">
        <div>{{ item.title }}</div>
        <div>Menge: {{ item.quantity }}</div>
        <div>Einzelpreis: {{ parseFloat(item.price_brutto) }}€</div>
        <div>Teilgesamtpreis: {{ (parseFloat(item.price_brutto) * item.quantity).toFixed(2) }}€</div>
        <button @click="removeFromWarenkorb(item.id)" class="entfernen-button">Entfernen</button>
      </div>
    </div>
    <div class="endsumme">
      <strong>Gesamtsumme: {{ gesamtsumme() }}€</strong>
    </div>

    <button @click="order()" class="bezahl-button">Bestellen</button>
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
    min-height: 45%;
    width: 50%;
    justify-content: center;
    align-items: center;
    position: relative;
    font-size: 1;
    border-radius: 5px;
    border: solid 1px white;
}
h1{
    font-size: calc(3em + 1vw);
    text-decoration: underline;
    margin: auto;
}                
.ausgabe{
    margin: auto;
    font-size: calc(.5em + 1vw);
}
.endsumme{
    margin: 5% 0 0 0;
    font-size: 300%;
    text-decoration: underline;
}
.entfernen-button{
  font-size: calc(.1em + 1vw);
  border-radius: 5px;
  font-weight: bold;
}
.bezahl-button{
    font-size: 250%;
    text-align: center;
    font-weight: bold;
    width: 25%;
    height: 100%;
    background-color: #4CAF50;
    color: white;
    border-radius: 5px;
    margin-top: 2%;
    margin-bottom: 2%;
}
</style>
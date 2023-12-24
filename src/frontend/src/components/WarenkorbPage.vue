<script setup lang="ts">
import { onMounted } from 'vue';
import { ref } from "vue";
import { store, removeFromWarenkorb, updateGesamtsumme, isloggedIn } from '@/store';
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

let checkLogin = () => {
  console.log(isloggedIn.value);

  if(isloggedIn.value){
    //bestellen
    console.log('bestellen');

    //request zum server und mit stripe bezahlen
  }
  else{
    //login
    alert('Um bestellen zu können, müssen Sie sich einloggen!');

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

    <button @click="checkLogin()">Bestellen</button>
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
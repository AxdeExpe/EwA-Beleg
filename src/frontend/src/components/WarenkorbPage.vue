<script setup lang="ts">
import { store, removeFromWarenkorb } from '@/store';

const warenkorb = store.value.warenkorb;

const gesamtsumme = () => {
  return warenkorb.reduce((total, item) => {
    return total + parseFloat(item.price_brutto) * item.quantity;
  }, 0).toFixed(2);
};
</script>

<template>
   <div class="item-box">
    <h1>Warenkorb</h1>
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
  </div>
</template>

<style scoped>
.item-box {
    display: flex;
    flex-direction: column;
    flex-wrap: wrap;
    background-color: rgb(0, 80, 133);
    color: white;
    min-height: 40%;
    /* width: 75%; */
    margin: 5vh 30vw 10em 30vw; /*top right bottom left*/
    padding: 0;
    position: relative;
    font-size: 1;
    border: 1px solid red;
}
h1{
    font-size: 300%;
    text-decoration: underline;
    margin-left: 1em;
}
.ausgabe{
    margin-left: 3em;
}
.endsumme{
    margin: 1em 0em 0em 1em; /*top right bottom left*/
    font-size: 300%;
    text-decoration: underline;
}
</style>
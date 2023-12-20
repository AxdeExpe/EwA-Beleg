<script setup lang="ts">
import { onMounted } from 'vue';
import { store, removeFromWarenkorb, updateGesamtsumme } from '@/store';

const warenkorb = store.value.warenkorb;

onMounted(() => {
  updateGesamtsumme();
});

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
    margin: auto; /*top right bottom left*/
    margin-top: 10vh;

    display: flex;
    flex-direction: column;

    background-color: rgb(0, 80, 133);
    color: white;
    min-height: 40%;
    width: 75%;
    justify-content: center;
    align-items: center;
    position: relative;
    font-size: 1;
    border: 1px solid red;

}

h1{
    font-size: 300%;
    text-decoration: underline;
    margin: auto;
    
}
.ausgabe{
    margin: auto;
    border: red 1px solid;
}
.endsumme{
    margin: auto;
    font-size: 300%;
    text-decoration: underline;
}

</style>
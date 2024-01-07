<script setup lang="ts">
import AdminBereichBox from '@/components/AdminBereich/AdminBereichBox.vue'
import { onMounted, ref } from 'vue';
import { username, password } from '@/store';

interface KatalogItem {
    id: number,
    title: string,
    oder_id: number,
    oder_date: string,
    amount: number,
    price: number,
    stripe_checkout_session_id: number,
    txn_id: number,
    customer_name: string,
    customer_email: string,
    username: string,
}

let login = ref({
  username: '',
  password: '',
})

let katalogItems = ref<Array<KatalogItem>>([]);

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
        title: 'title',
        oder_id: 'oder_id',
        oder_date: 'oder_date',
        amount: 'amount',
        price: 'price',
        stripe_checkout_session_id: 'stripe_checkout_session_id',
        txn_id: 'txn_id',
        customer_name: 'customer_name',
        customer_email: 'customer_email',
        username: login.value.username,
        password: login.value.password,
      }),
    });
    if (response.status === 200) {
        let data = await response.json();
        katalogItems.value = data['Orders'].map((item: KatalogItem) => ({ ...item }));
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


const formatFieldName = (fieldName: string) => {
  // Beispiel: 'oder_id' wird zu 'Bestellungs ID'
  // Hier können Sie die Anpassungen für Ihre Feldnamen vornehmen
  const formattedFieldNames: Record<string, string> = {
    id: 'ID:',
    title: 'Titel:',
    oder_id: 'Bestellungs ID:',
    oder_date: 'Bestellungs Datum:',
    amount: 'Anzahl:',
    price: 'Preis (€):',
    stripe_checkout_session_id: 'Stripe Checkout Session ID:',
    txn_id: 'Transaktions ID:',
    customer_name: 'Kundenname:',
    customer_email: 'Kunden E-Mail:',
    username: 'Username:',
  };

  return formattedFieldNames[fieldName] || fieldName;
};
</script>

<template>
    <div>
        <AdminBereichBox>
            <template v-slot:Katalogverwaltung>
                <div v-for="(item) in katalogItems" :key="item.id" class="book-table">
                    <div class="book-tablerow" v-for="(value, key) in item" :key="key">
                        <div class="book-tablecell">{{ formatFieldName(key) }}</div>
                        <div class="book-tablecell">{{ value }}</div>
                    </div>
                </div>
            </template>
        </AdminBereichBox>
    </div>
</template>

<style scoped>
.book-table{
  width: 100%;
  border: 2px solid #ffffff;
  display: table;
  table-layout: fixed;
  background-color: blue;
  font-weight: bold;
  font-size: calc(10px + 2vmin);
  margin-bottom: 10px;
  color: white;
  word-wrap: break-word;
  overflow: hidden;
}
.book-tablerow {
  display: table-row;
}
.book-tablecell {
  padding: 5px;
  display: table-cell;
  width: 20%;
}
.book-tablecell:last-child {
  width: 80%;
}
@media screen and (max-width: 1000px) {
  .book-table{
    float: none !important;
    border: 1px solid yellow;
    width: 100% !important;
  }
  .book-table{
    background-color: red;
  }
}
/* @media screen and (max-width: 1000px) {
  .links,
  .mitte,
  .rechts
  {
    float: none !important;
    border: 1px solid yellow;
    width: 100% !important;
  }
  .grid-container{
    background-color: red;
  }
  .rechts{
    text-align: left !important;
  }
} */
</style>
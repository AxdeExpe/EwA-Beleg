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
    id: 'ID',
    title: 'Titel',
    oder_id: 'Bestellungs ID',
    oder_date: 'Bestellungs Datum',
    amount: 'Anzahl',
    price: 'Preis',
    stripe_checkout_session_id: 'Stripe Checkout Session ID',
    txn_id: 'Transaktions ID',
    customer_name: 'Kundenname',
    customer_email: 'Kunden E-Mail',
    username: 'Username',
  };

  return formattedFieldNames[fieldName] || fieldName;
};
</script>

<template>
    <div>
        <AdminBereichBox>
            <template v-slot:Katalogverwaltung>
                <div v-for="(item) in katalogItems" :key="item.id" class="item-container">
                    <div class="item" v-for="(value, key) in item" :key="key">
                        <div class="label">{{ formatFieldName(key) }}</div>
                        <div class="value">{{ value }}</div>
                    </div>
                </div>
            </template>
        </AdminBereichBox>
    </div>
</template>

<style scoped>
.item-container {
  display: grid;
  grid-template-columns: 1fr; /* Anpassen Sie dies je nach gewünschter Anzahl von Spalten */
  gap: 10px; /* Abstand zwischen den Elementen */
  background-color: rgb(0, 80, 133);
  color: white;
  margin: 15px 0 0 0;
  padding: 0px 0px 5px;
  cursor: pointer;
}
.item {
  display: flex;
  justify-content: space-between;
  padding: 8px;
}
.label {
  font-weight: bold;
}
.value {
  margin-left: 10px;
}
/* .item-box {
    display: grid;
    grid-template-columns: 35% 65%;
    grid-template-rows: repeat(11, 30px);
    font-size: calc(.1em + .18w);
    justify-content: space-between;
    background-color: rgb(0, 80, 133);
    color: white;
    margin: 15px 0 0 0; /*top right bottom left
    padding: 0px 0px 5px;
    position: relative;
    cursor: pointer;
} */
/* .tabelle {
    border: 1px solid red;
    background-color: blue;
    color: white;
    margin-top: 5px;
} */
</style>
<script setup lang="ts">
import AdminBereichBox from '@/components/AdminBereich/AdminBereichBox.vue'
import { password, username } from '@/store';
import { ref } from 'vue'

let formData = ref({
  username: '',
  password: '',
  id: '',
  image: '',
  title: '',
  author: '',
  price_netto: '',
  mwst: '',
  weight: '',
  stock: '',
  description: '',
  publisher: ''
})

const submitForm = async () => {
  try {
    formatPrice();
    formData.value.username = username;
    formData.value.password = password;

    const response = await fetch('https://ivm108.informatik.htw-dresden.de/ewa/g08/backend/admin_insert_book.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: new URLSearchParams(formData.value).toString()
    })

    if (response.ok) {
      console.log('Formular erfolgreich gesendet!');
      alert('Buch wurde hinzugefügt!');
    } else {
      console.error('Fehler beim Senden des Formulars:', response.statusText)
    }
  } catch (error: any) {
    console.error('Fehler beim Senden des Formulars:', error.message)
  }
}

const formatPrice = () => {
  // Überprüfen, ob formData.price_netto eine Zahl ist
  if (!isNaN(parseFloat(formData.value.price_netto))) {
    // Konvertieren und auf zwei Dezimalstellen runden
    formData.value.price_netto = parseFloat(formData.value.price_netto).toFixed(2);
  } else {
    // Wenn keine Zahl eingegeben wurde, auf den Standardwert setzen
    formData.value.price_netto = '0.00';
  }
}
</script>

<template>
    <div>
        <AdminBereichBox>
          <template v-slot:Bucheinfügen>
              <form class="daten-bearbeitung" @submit.prevent="submitForm">
                <div class="e">Bitte die Daten des neuen Buches eintragen</div>
                <div class="bild">Bildpfad:</div>
                <div class="buchtitel">Buchtitel:</div>
                <div class="author">Autor:</div>
                <div class="verlag">Verlag:</div>
                <div class="beschreibung">Beschreibung:</div>
                <div class="preis">Preis-netto:</div>
                <div class="gewicht">Gewicht: (in g)</div>
                <div class="lagerbestand">Lagerbestand:</div>
                <input v-model="formData.image" type="text" class="input1" required placeholder="../example/example.png">
                <input v-model="formData.title" type="text" class="input2" required>
                <input v-model="formData.author" type="text" class="input3" required>
                <input v-model="formData.publisher" type="text" class="input4" required>
                <input v-model="formData.description" type="text" class="input5" required>
                <input v-model="formData.price_netto" type="number" class="input6" min="0" step="0.01" placeholder="0.00" required>
                <input v-model="formData.weight" type="number" class="input7" min="0" required>
                <input v-model="formData.stock" type="number" class="input8" min="0" required>
                <button type="submit" class="submit-button">Submit</button>
              </form>
          </template>
        </AdminBereichBox>
    </div>
</template>

<style scoped>
.daten-bearbeitung{
    display: grid;
    grid-template-columns: 15% 32% 15% 32%;
    grid-template-rows: repeat(4, 60px);
    grid-gap: 5px;
    justify-content: center;
    align-items: center;
    margin-right: 0.1em;
}
.e{
    grid-column: 1 / span 4;
    grid-row: 1;
    text-align: center;
    font-size: 30px;
    font-weight: bold;
}
.bild{
    grid-column: 1;
    grid-row: 2;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
}
.buchtitel{
    grid-column: 3;
    grid-row: 2;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
}
.author{
    grid-column: 1;
    grid-row: 3;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
}
.verlag{
    grid-column: 3;
    grid-row: 3;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
}
.beschreibung{
    grid-column: 1;
    grid-row: 4;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
}
.preis{
    grid-column: 3;
    grid-row: 4;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
}
.gewicht{
    grid-column: 1;
    grid-row: 5;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
}
.lagerbestand{
    grid-column: 3;
    grid-row: 5;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
}
.input1{
    grid-column: 2;
    grid-row: 2;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
    width: 100%;
}
.input2{
    grid-column: 4;
    grid-row: 2;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
    width: 100%;
}
.input3{
    grid-column: 2;
    grid-row: 3;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
    width: 100%;
}
.input4{
    grid-column: 4;
    grid-row: 3;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
    width: 100%;
}
.input5{
    grid-column: 2;
    grid-row: 4;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
    width: 100%;
}
.input6{
    grid-column: 4;
    grid-row: 4;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
    width: 100%;
}
.input7{
    grid-column: 2;
    grid-row: 5;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
    width: 100%;
}
.input8{
    grid-column: 4;
    grid-row: 5;
    font-size: calc(.5em + 1vw);
    text-align: left;
    font-weight: bold;
    width: 100%;
}
.submit-button{
    grid-column: 1 / span 2;
    grid-row: 7;
    font-size: calc(.5em + 1vw);
    text-align: center;
    font-weight: bold;
    width: 100%;
    height: 100%;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 15px 32px;
    text-decoration: none;
    display: inline-block;
}
</style>


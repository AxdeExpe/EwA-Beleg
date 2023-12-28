// store.ts
import { ref } from 'vue';

interface KatalogItem {
    id: number;
    image: string;
    title: string;
    author: string;
    publisher: string;
    description: string;
    weight: number;
    price_brutto: string;
    stock: number;
    quantity: number;
}

export const isloggedIn = ref(false);
export const is_admin = ref(false);

export const updateIsloggedIn = (value: boolean) => {
  isloggedIn.value = value;
};

export const updateIsAdmin = (value: boolean) => {
  is_admin.value = value;
};

export let username: string;
export let password: string;

export let updateUsername = (value: string)=>{
  username = value;
} 

export let updatePassword = (value: string)=>{
  password = value;
}


// interface WarenkorbItem extends KatalogItem {
//     quantity: number;
// };

interface Store {
  warenkorb: KatalogItem[];
}

export const store = ref<Store>({
  warenkorb: [],
});

export const addToWarenkorb = (item: KatalogItem) => {
  // Hier kannst du deine Logik für die Überprüfung des Lagerbestands usw. hinzufügen
  // store.value.warenkorb.push({ ...item, quantity: item.quantity });
  const existingItemIndex = store.value.warenkorb.findIndex((cartItem) => cartItem.id === item.id);

  if (existingItemIndex !== -1) {
    store.value.warenkorb[existingItemIndex].quantity += item.quantity;
  } else {
    store.value.warenkorb.push({ ...item, quantity: item.quantity });
  }
  updateGesamtsumme();
};

export const removeFromWarenkorb = (itemId: number) => {
    const index = store.value.warenkorb.findIndex(item => item.id === itemId);
    if (index !== -1) {
      store.value.warenkorb.splice(index, 1);
    }
};

export const gesamtsumme = ref<string>('0.00');

export const updateGesamtsumme = () => {
    const newGesamtsumme = store.value.warenkorb.reduce((total, item) => {
      return total + parseFloat(item.price_brutto) * item.quantity;
    }, 0).toFixed(2);
    gesamtsumme.value = newGesamtsumme;
};
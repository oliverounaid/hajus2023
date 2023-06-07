<template>
  <Head title="Map" />
  <Navbar />
  <div class=" w-full h-96 flex">
    <div class="h-full w-full mx-6 my-6 rounded-lg lg:w-6/12" id="map" ref="map"></div>
    <div class="flex">
      <form class="flex flex-col w-72 mt-6 mx-6 gap-2" @submit.prevent="submit">
        <label for="">Name</label>
        <input class="rounded-lg" type="text" v-model="form.name" required>
        <label for="">Desc</label>
        <input class="rounded-lg" type="text" v-model="form.desc">
        <label for="">Lat</label>
        <input class="rounded-lg" type="text" v-model="form.lat" required>
        <label for="">Lng</label>
        <input class="rounded-lg" type="text" v-model="form.lng" required>
        <button type="submit" class="bg-gray-800 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg">{{
          form.marker_id ? 'UPDATE MARKER' : 'ADD MARKER' }}</button>
      </form>
    </div>
  </div>
  <table class="table-fixed mt-8 mx-2 text-left w-1/2">
    <thead>
      <tr>
        <th class="w-1/4">Name</th>
        <th class="w-1/4">Desc</th>
        <th class="w-1/4">Lat</th>
        <th class="w-1/4">Lng</th>
        <th class="w-1/4">Actions</th>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(marker, index) in markers" :key="index" class="border">
        <td class="border">{{ marker.name }}</td>
        <td class="border">{{ marker.description }}</td>
        <td class="border">{{ marker.latitude }}</td>
        <td class="border">{{ marker.longitude }}</td>
        <td class="flex">
          <button class="bg-gray-800 hover:bg-gray-600 text-white font-bold py-2 px-4 rounded-lg mr-2 w-[100%]"
            @click="editMarker(index)">EDIT</button>
          <button class="bg-red-500 hover:bg-red-400 text-white font-bold py-2 px-4 rounded-lg"
            @click="deleteMarker(index)">DELETE</button>
        </td>
      </tr>
    </tbody>
  </table>
</template>

<script setup>
import { Loader } from "@googlemaps/js-api-loader";
import { useForm } from "@inertiajs/vue3";
import Navbar from '../Components/Navbar.vue';
import { inject, onMounted, ref } from "vue";
import axios from 'axios';
import { Head } from '@inertiajs/vue3';

const route = inject('route');

const props = defineProps({
  markers: {
    type: Array,
    default: null,
  },
});

let map = ref(null);
let prevMarker;
let gmap;

const form = useForm({
  name: '',
  desc: '',
  lat: null,
  lng: null,
  marker_id: null
});

let markers = ref(props.markers || []);


const loader = new Loader({
  apiKey: '',
  version: "weekly",
});


onMounted(() => {
  loader.load().then(() => {
    let myLatLng = { lat: 58.77028, lng: 	24.89177 };
    gmap = new google.maps.Map(map.value, {
      zoom: 7,
      center: myLatLng,
    });

    
    axios.get(route('markers.index'))
      .then(response => {
        
        markers.value = response.data;
        markers.value.forEach((marker) => {
          const newMarker = new google.maps.Marker({
            position: { lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude) },
            map: gmap,
          });

          newMarker.addListener('click', () => {
            const infowindow = new google.maps.InfoWindow({
              content: `
                <div>
                  <h3>${marker.name}</h3>
                  <p>${marker.description ? marker.description : ''}</p>
                </div>
              `,
            });
            infowindow.open(gmap, newMarker);
          });
        });
      })
      .catch(error => console.log(error));

    gmap.addListener("click", (mapsMouseEvent) => {
      if (prevMarker) {
        prevMarker.setMap(null);
      }

      let data = mapsMouseEvent.latLng.toJSON();
      prevMarker = new google.maps.Marker({
        position: data,
      });
      form.lng = data.lng
      form.lat = data.lat
      if (prevMarker) {
        prevMarker.setMap(gmap);
      }
    });
  });

});

const submit = () => {
  const formData = new FormData();
  formData.append('name', form.name);
  formData.append('description', form.desc);
  formData.append('latitude', form.lat);
  formData.append('longitude', form.lng);

  if (form.marker_id) {
    form.put(route('markers.update', { id: form.marker_id }), formData, {
      onSuccess: (response) => {
        console.log(response);
        const updatedMarkerIndex = markers.value.find((marker) => marker.id === form.marker_id);
        const updatedMarker = response.data;
        markers.value[updatedMarkerIndex] = updatedMarker;
        form.name = '';
        form.desc = '';
        form.lat = null;
        form.lng = null;
        form.marker_id = null;
      },
    });
  } else {
    form.post(route('markers.store'), formData, {
      onSuccess: (response) => {
        console.log(response);
        const newMarker = {
          id: response.id,
          name: form.name,
          description: form.desc,
          latitude: form.lat,
          longitude: form.lng,
        };
        markers.value.push(newMarker);
        const marker = new google.maps.Marker({
          position: { lat: parseFloat(form.lat), lng: parseFloat(form.lng) },
          map: gmap,
        });
        console.log(marker);
        marker.addListener('click', () => {
          const infowindow = new google.maps.InfoWindow({
            content: `
              <div>
                <h3>${form.name}</h3>
                <p>${form.desc}</p>
              </div>
            `,
          });
          infowindow.open(gmap, marker);
        });
        form.name = '';
        form.desc = '';
        form.lat = null;
        form.lng = null;
      },
    });
  }
};

const editMarker = (index) => {
  const marker = markers.value[index];
  form.name = marker.name;
  form.desc = marker.description;
  form.lat = marker.latitude;
  form.lng = marker.longitude;
  form.marker_id = marker.id;
};

const deleteMarker = (index) => {
  const marker = markers.value[index];
  axios.delete(route('markers.destroy', { marker: marker.id }))
    .then(() => {
      markers.value.splice(index, 1);
      if (marker && marker.setmap) {
        marker.gmapMarker.setMap(null);
      }
      form.reset();
    })
    .catch((error) => {
      console.log(error);
    });
};
</script>  
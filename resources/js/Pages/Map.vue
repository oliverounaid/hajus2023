<template>
  <Navbar />
  <div class="lg:w-7/12 w-full h-96">
    <div class="h-full w-full mx-2 my-2" id="map" ref="map"></div>
    <form class="grid w-72 gap-4 mt-4 mx-6" @submit.prevent="submit">
      <label for="">Name</label>
      <input type="text" v-model="form.name" required>
      <label for="">Desc</label>
      <input type="text" v-model="form.desc">
      <label for="">Lat</label>
      <input type="text" v-model="form.lat" required>
      <label for="">Lng</label>
      <input type="text" v-model="form.lng" required>
      <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">{{ form.marker_id ? 'Update Marker' : 'Add Marker' }}</button>
    </form>
    <table class="table-fixed mt-8 mx-2 text-left">
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
          <td>
            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2 w-[100%]" @click="editMarker(index)">Edit</button>
            <button class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded" @click="deleteMarker(index)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { Loader } from "@googlemaps/js-api-loader";
import { useForm } from "@inertiajs/vue3";
import Navbar from '../Components/Navbar.vue';
import { inject, onMounted, ref } from "vue";
import axios from 'axios';

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

//Google maps conf
const loader = new Loader({
  apiKey: '',
  version: "weekly",
});

//map käivitatakse
onMounted(() => {
  loader.load().then(() => {
    let myLatLng = { lat: 58.24806, lng: 22.50389 };
    gmap = new google.maps.Map(map.value, {
      zoom: 8,
      center: myLatLng,
    });

    // Fetch markers from the database
    axios.get(route('markers.index'))
      .then(response => {
        // Add fetched markers to the map
        markers.value = response.data;
        markers.value.forEach((marker) => {
          const newMarker = new google.maps.Marker({
            position: { lat: parseFloat(marker.latitude), lng: parseFloat(marker.longitude) },
            map: gmap,
          });

          // Add event listener to open the infowindow on click
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
  // Update an existing marker
  form.put(route('markers.update', { id: form.marker_id }), formData, {
    onSuccess: (response) => {
      console.log(response);
      // Update the marker on the map
      const updatedMarkerIndex = markers.value.find((marker) => marker.id === form.marker_id);
      const updatedMarker = response.data;
        markers.value[updatedMarkerIndex] = updatedMarker;
      // Clear the form
      form.name = '';
      form.desc = '';
      form.lat = null;
      form.lng = null;
      form.marker_id = null;
    },
  });
  } else {
    // Create a new marker
    form.post(route('markers.store'), formData, {
      onSuccess: (response) => {
        console.log(response);
  
        // Create a new marker on the map
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
        // Add event listener to open the infowindow on click
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
        // Clear the form
        form.name = '';
        form.desc = '';
        form.lat = null;
        form.lng = null;
      },
    });
  }
};


// Function to edit a marker
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
      // Remove the marker from the markers array
      markers.value.splice(index, 1);
      // Remove the marker from the map
      if (marker && marker.setmap) {
        marker.gmapMarker.setMap(null);
      }
      // Clear the form
      form.reset();
    })
    .catch((error) => {
      console.log(error);
    });
};

</script>  
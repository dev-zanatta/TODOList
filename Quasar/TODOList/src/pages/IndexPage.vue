<template>
  <q-page class="">
    <q-input v-model="newTask" filled bottom-slots placeholder="Add new tasks" @keyup.enter="addNewTask">

      <template v-slot:append>
        <q-btn @click="addNewTask(task)" round dense flat icon="add" />
      </template>
    </q-input>
    <q-list class="bg-grey-4" separator bordered>
      <!--
        Rendering a <label> tag (notice tag="label")
        so QCheckboxes will respond to clicks on QItems to
        change Toggle state.
      -->

      <q-item
      v-for="task in tasks" :key="task.title" @click="task.finished=!task.finished" :class="{  'finished': task.finished}" v-ripple>
        <q-item-section avatar>
          <q-checkbox @click="updateTask(task)" v-model="task.finished"/>
        </q-item-section>
        <q-item-section>
          <q-item-label>{{task.title}}</q-item-label>
        </q-item-section>
        <q-item-section v-if="task.finished" side>
          <q-btn @click.stop="finishTask(task)" flat color="secondary" icon="delete" />
        </q-item-section>
      </q-item>

    </q-list>
  </q-page>
</template>

<script>
import { defineComponent } from 'vue'
// import { response } from 'express'

export default defineComponent({
  name: 'IndexPage',
  data () {
    return {
      newTask: '',
      tasks: []
    }
  },
  mounted () {
    this.getTasks()
  },
  methods: {
    finishTask (task) {
      const index = this.tasks.indexOf(task)
      this.tasks.splice(index, 1)
      fetch('http://127.0.0.1:8000/api/task/' + task.id, {
        method: 'DELETE',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json'
        }
      })
        .catch((error) => {
          console.log(error)
        })
    },
    async updateTask (taskUp) {
      await fetch('http://127.0.0.1:8000/api/task/' + taskUp.id, {
        method: 'PUT',
        credentials: 'same-origin'
      })
        .then(response => { console.log(response.json()) })
        .catch((error) => {
          console.log(error)
        })
    },
    async addNewTask (task) {
      const add = {
        title: this.newTask,
        finished: false
      }
      await fetch('http://127.0.0.1:8000/api/task/store', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(add)
      })
        .then((response) => {
          console.log(response.json())
        })
        .catch((error) => {
          console.log(error)
        })
      this.newTask = ''
      this.getTasks()
    },
    getTasks () {
      fetch('http://127.0.0.1:8000/api/tasks')
        .then(response => { return response.json() })
        .then(data => {
          this.tasks = data
          for (let i = 0; i < this.tasks.length; i++) {
            if (this.tasks[i].finished === 0) {
              this.tasks[i].finished = false
            } else this.tasks[i].finished = true
          }
        })
    }
  }
})

</script>
<style>
.finished{
  color: rgb(102, 102, 102);
}
</style>

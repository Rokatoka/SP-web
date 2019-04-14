<template>
    <!-- Поиск -->
    <div class="col-2 offset-2 fixed-top h-100 pt-4">
        <div class="form-group">
            <select class="form-control" v-model="filterData.field">
                <option value="status">Status</option>
                <option value="name">Name</option>
                <option value="date">Date</option>
            </select>
        </div>
        <div class="form-group" v-if="filterData.field == 'status'">
            <select class="form-control" v-model="filterData.status">
                <option value="approved">Approved</option>
                <option value="canceled">Canceled</option>
                <option value="done">Done</option>
                <option value="missed">Missed</option>
            </select>
        </div>
        <div class="form-group" v-if="filterData.field != ''">
            <input type="text" v-model="filterData.search_text" class="form-control form-control-sm" placeholder="Name, date">
        </div>

        <div class="form_group">
            <button @click="clearListLoad()" :disabled="load" class="btn btn-primary btn-block" >Apply</button>
            <button @click="resetFilter()" :disabled="load" class="btn btn-secondary btn-block btn-sm">Cancel Filter</button>
        </div>
    </div>
</template>


<script>

    export default {

        data(){
            return{
                filterData: {
                    search_text: '',
                    field:'',
                    status:''
                },
            }
        },

        methods:{
            resetFilter(){
                this.filterData.search_text = '';


                this.clearListLoad();
            },
            clearListLoad(){

                this.$nextTick(function () {
                    this.$emit('filtered');
                });

            },
        }
    }

</script>
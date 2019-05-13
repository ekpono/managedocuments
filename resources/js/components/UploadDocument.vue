<template>
    <div>
        <div class="card-header">Dashboard<span class="float-right"><button class="btn btn-success" @click.prevent="fileUploadModal()">Upload Document</button></span></div>
        <div class="card-body" v-if="! documents.length">
            <h4>You have not upload documents yet</h4>
        </div>
        <div class="card-body" v-for="document in documents">
            <a :href="document.documents.document">{{document.documents.document}}</a>
        </div>
        <div class="card-body">
        <!--<p class="font-bold h4 text-muted pt-4"> click <a class="text-success mt-4" href="#" @click.prevent="fileUploadModal()">here</a> to upload required files</p>-->
            <div class="modal fade mt-4" id="uploadDealDocumentModal" tabindex="-1" role="dialog" aria-labelledby="uploadDealDocumentModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="text-muted text-center mt-5 mb-3">5MB Max</h5>
                    <div class="input-group" v-for="(uploadRow, index) in uploadRows" :key="index" :uploadRow="uploadRow">
                        <div class="col-2"></div>
                        <div class="custom-file dropbox">
                            <input @change="documentFieldChange($event)" type="file">
                            <h4 @click="deleteUploadRow(index)" class="text-danger ml-3 pt-1" style="cursor: pointer">X</h4>
                            <div class="col-2"></div>
                        </div>
                    </div>
                    <h5 @click.prevent="addUploadRow()" style="cursor: pointer" class="mt-4 text-center text-muted"><span class="text-success h2">+</span> Add File</h5>

                </div>
                <div class="modal-footer">
                    <button  type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button @click.prevent="uploadDealDocumentFiles()" type="submit" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
        </div>
    </div>
</template>

<script>
    import {toast} from "../helpers/helpers";
    export default {
        data() {
            return {
                documents: {},
                uploadRows: [{
                    id: 'deal0',
                    value: '',
                }],
                counter: 0,
                documentAttachments: []
            }
        },
        created() {
            this.fetchDocuments();
        },
        methods: {
            fileUploadModal() {
                $('#uploadDealDocumentModal').modal('show')
            },
            documentFieldChange(e){
                console.log(e)
                let selectedFiles=e.target.files;
                if(!selectedFiles.length){
                    return false;
                }
                for(let i=0;i<selectedFiles.length;i++){
                    this.documentAttachments.push(selectedFiles[i]);
                }
                console.log(this.documentAttachments);
            },
            uploadDealDocumentFiles(){
                let documentFormData = new FormData;
                for(let i=0; i<this.documentAttachments.length;i++){
                    documentFormData.append('documents[]',this.documentAttachments[i]);
                }
                const config = { headers: { 'Content-Type': 'multipart/form-data' } };
                this.uploadRows = [];
                this.addUploadRow();
                axios.post('/uploadDocuments',documentFormData,config).then(res=>{
                    $('#uploadDealDocumentModal').modal('hide')
                    this.documents = res.data.data;
                    toast(res.data.success, 'success')
                    console.log(this.documents)
                })
                    .catch(response=>{
                        //error
                    });
            },
            deleteUploadRow: function(index){
                this.uploadRows.splice(index, 1);
            },
            fetchDocuments() {
                axios.get('/fetchMyDocuments').then(res => {
                    this.documents = JSON.parse(res.data.data);
                })
            },
            addUploadRow: function() {
                this.uploadRows.push({
                    id: `deal${++this.counter}`,
                    value: '',
                });
            },
        }
    }
</script>

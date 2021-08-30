import { Component, OnInit } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { DocuemntacionSindicalService } from '../../../servicios/docuemntacion-sindical.service';

@Component({
  selector: 'app-formulario-documentacion',
  templateUrl: './formulario-documentacion.component.html',
  styleUrls: ['./formulario-documentacion.component.css']
})
export class FormularioDocumentacionComponent implements OnInit {

  form:FormGroup;
  archivo=null;
  tipos:any;
  btn:boolean=false;
  constructor(private fb:FormBuilder, private _documentacion:DocuemntacionSindicalService) {
    this.construir_form();
   }

  ngOnInit() {
    this.listado_tipos();
  }
  construir_form(){
    this.form = this.fb.group({
      titulo:['', Validators.required],
      archivo:[null, Validators.required],
      tipo_documento:['', Validators.required]
    });
  }
  register(){
    this.btn = true;
   if(this.form.valid){
    console.log(this.form.value);
    const fd = new FormData();
    fd.append('titulo',this.form.controls.titulo.value);
    fd.append('archivo',this.archivo);
    fd.append('tipo_documento',this.form.controls.tipo_documento.value);
    this._documentacion.registro(fd).subscribe(res=>{
      if(res.estado){

        this.construir_form();
        this.archivo = null;
        alert(res.mensaje);
        this.btn = false;
      }
    }, error =>{
      alert("Error de servidor");
      this.btn = false;
    });
   }else{
     alert("faltan campos por llenar")
     this.btn = false;
   }
  }
  listado_tipos(){
    this._documentacion.listado_tipos().subscribe(res =>{
      this.tipos = res;
    })
  }

  onSelectImage(event) {
    this.archivo= event.srcElement.files[0];
  }

}

import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { global } from '../../../servicios/global';
import { ValidarUsuarioService } from '../../../servicios/validar-usuario.service';

@Component({
  selector: 'app-formulario-socios',
  templateUrl: './formulario-socios.component.html',
  styleUrls: ['./formulario-socios.component.css']
})
export class FormularioSociosComponent implements OnInit 
{

  url:string;
  selectedImage:File;

  foto:File;
  rut:string='';
  fecha_nacimiento:string='';
  fecha_ingreso:string='';
  nombres:string='';
  a_paterno:string='';
  a_materno:string='';

  token = localStorage.getItem('token').replace(/['"]+/g, '');

  load:boolean = false;
  modalReference = null;
  user:object=[];

  constructor(public _http:HttpClient, private _validarusuario:ValidarUsuarioService,private modalService: NgbModal) {
  this.url = global.url;
   }

  ngOnInit() {
    this.usuario_logeado();
  }

  onSelectImage(event) {
    //console.log(event.srcElement.files[0])

     this.selectedImage = event.srcElement.files[0];
      //console.log(this.selectedImage);
  }


  onSubmit()
  {
    
    const form = new FormData();
    form.append('foto', this.selectedImage);
    form.append('rut',this.rut);
    form.append('fecha_nacimiento', this.fecha_nacimiento);
    form.append('fecha_ingreso', this.fecha_ingreso);
    form.append('nombres',this.nombres);
    form.append('a_paterno', this.a_paterno);
    form.append('a_materno',this.a_materno);
    console.log(form);
    this._http.post(this.url + "ingresar_socio",form,{ headers: new HttpHeaders(
      {
          'Authorization': 'Bearer' + this.token,
          // 'Content-Type': 'multipart/form-data'
      }
    )}).subscribe((val : {'estado','mensaje'} ) => {
        if (val.estado == "success") {
          alert(""+val.mensaje+""); 
         this.rut = '';
         this.fecha_nacimiento = '';
         this.fecha_ingreso = '';
         this.nombres = '';
         this.a_paterno = '';
         this.a_materno = '';
          return false;

        }
        if (val.estado == "failed") {
          alert(""+val.mensaje+""); return false;
        }
        if (val.estado == "failed_v") {
          alert(""+val.mensaje+""); return false;
        }
      }, response => {
          console.log("POST call in error", response);

      },
      () => {
        console.log("The POST observable is now completed.");
    });
  }





  usuario_logeado(){
      
      this._validarusuario.usuario_logeado().subscribe((val : object ) => {
            
            this.user = val;

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
  }

  validar_usuario(validar){
    this.modalReference = this.modalService.open(validar, { size: 'sm' });

      //  validar = this.validar_inputs();

      // if (validar == true) {
      //   this.modalReference = this.modalService.open(validar, { size: 'sm' });
      // }

       
  }


  btn_validar_usuario($rut, $password, validar){//btn que esta en el modal de validacion de usuario
        this.load = true;
        const formData = new FormData();
        formData.append('rut', $rut.value);
        formData.append('password', $password.value);

        this._validarusuario.validar_usuario(formData).subscribe((val) => {
            
            if(val > 0){//si tiene acceso;
            
            this.load = false;
              
             this.modalReference.close();
             this.onSubmit();
            }else{
              alert("Acceso denegado");
              this.load = false;
              this.modalReference.close();
            }

        }, response => {console.log("POST call in error", response);},() => {
               console.log("The POST success.");
        });
    }

    validar_inputs(validar){

       
        if (this.rut == '') { alert("Ingrese rut"); return false }
        if (this.fecha_nacimiento == '') { alert("Ingrese fecha de nacimiento"); return false }
        if (this.fecha_ingreso == '') { alert("Ingrese fecha de ingreso"); return false }
        if (this.nombres == '') { alert("Ingrese nombres"); return false }
        if (this.a_paterno == '') { alert("Ingrese apellido paterno"); return false }
        if (this.a_materno == '') { alert("Ingrese apellido materno"); return false }


        this.modalReference = this.modalService.open(validar, { size: 'sm' });
        return true;

    }

}

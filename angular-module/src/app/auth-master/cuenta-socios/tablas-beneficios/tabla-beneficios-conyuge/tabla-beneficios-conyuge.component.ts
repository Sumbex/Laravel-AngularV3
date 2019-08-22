import { Component, OnInit,Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';

@Component({
  selector: 'app-tabla-beneficios-conyuge',
  templateUrl: './tabla-beneficios-conyuge.component.html',
  styleUrls: ['./tabla-beneficios-conyuge.component.css']
})
export class TablaBeneficiosConyugeComponent implements OnInit {

  abrirTablaBeneficiosConyuge;
  abrirDocumento;
  UpdDocumento;

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  traerDatosConyuge:object = [
                    'rut',
                    'fecha_nacimiento',
                    'nombres',
                    'apellido_paterno',
                    'apellido_materno',
                    'direccion',
                    'celular',
                    'created_at',
                    'updated_at',
                    'archivo',
                    ];
vista_tabla: boolean = false;

//validar user 
user:object=[];
load:boolean=false;
validarModalActualizar = null;

m_val = null;
closeResult: string;
pass:string = '';
buttonStatus = false;

actualizarLoad:boolean=false;
vista_pdf:boolean=false;
DocumentoConyuge;
entrada;
    

  constructor(config: NgbModalConfig, 
  private modalService: NgbModal,
  private _SociosService:SociosService,
  private _validarusuario:ValidarUsuarioService) {
  config.backdrop = 'static';
  config.keyboard = false;
                      }

  ngOnInit() {
  }

  verTablaBeneficios(TablaBeneficios) {
    this.abrirTablaBeneficiosConyuge = this.modalService.open(TablaBeneficios, { size: 'xl' });
    this.listarDatosConyuge();
    this.usuario_logeado();
  }

  verDocumento(Documento) {
    this.abrirDocumento = this.modalService.open(Documento, { size: 'lg' });
    this.traerDocumentoConyuge();
  }
  onSelectImage(event) {
    this.UpdDocumento = event.srcElement.files[0];
  }

  traerDocumentoConyuge(){
    this.vista_pdf = true;
    this._SociosService.getRegistroCivil(this.getIdSocio).subscribe((response) =>{
      // console.log(response);
     if(response.estado == "failed"){
       this.vista_pdf = false;
       alert(response.mensaje);
       this.abrirDocumento.close();
       return false;
     }else{
       this.DocumentoConyuge = response.body[0].archivo;
      //  console.log(this.TraerDocumentoSocio);
       this.vista_pdf = false;
     }
 
        error => {
       console.log(error);
       this.vista_pdf = false;
       }
     }
   );
   }

  limpiar_tabla(){
    this.vista_tabla = false;
  }

  listarDatosConyuge(){
    this._SociosService.getDatosConyuge(this.getIdSocio).subscribe((response) =>{
      console.log(response);
     if(response.estado == "failed"){
      //  alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
       this.vista_tabla = false;
       alert(response.mensaje);
       this.abrirTablaBeneficiosConyuge.close();
       return false;
     }else{
       this.traerDatosConyuge = response.body;
       this.vista_tabla = true;
     }
 
        error => {
       console.log(error);
       this.vista_tabla = false;
       }
     }
   );
   }
     //metodos para validar usuario-------------------------------
 
     usuario_logeado(){
       
       this._validarusuario.usuario_logeado().subscribe((val : object ) => {
             
             this.user = val;
   
         }, response => {console.log("POST call in error", response);},() => {
                console.log("The POST success.");
         });
   }
   
     //actualizar items
     actualizar(campo,valor,validar){

      if(campo == "archivo"){
        if(valor.value == null){
          alert("ingrese documento porfavor!")
          return false;
        }
        this.entrada = this.UpdDocumento; 
       }else{
         this.entrada = valor.value;
       }
       if(this.entrada == ''){
         alert("ingrese datos porfavor!");
         return false;
       }
 
       this.m_val = this.modalService.open(validar, {size: 'sm', ariaLabelledBy: 'modal-basic-title'}).result.then((result) => {
           
         this.m_val = this.modalService.open(validar, { size: 'sm' });
         this.load = true;
         this.buttonStatus = true;
   
         const formData = new FormData();
         formData.append('rut', this.user['rut']);
         formData.append('password', this.pass);
   
         this._validarusuario.validar_usuario(formData).subscribe((val) => {
               
             //si tiene acceso
             if(val > 0){
             
               this.load = false;
               this.buttonStatus = false;
               this.pass = "";
               
              this.m_val.close();
              
                   const data = new FormData();
                   data.append('id', this.getIdSocio);
                   data.append('campo',campo);
                   data.append('valor',this.entrada);
   
                   this.actualizarLoad = true;
                   this._SociosService.ActualizarDatosConyuge(data).subscribe(
                       response => {
                         if (response.estado == "success") {
                          alert(response.mensaje);
                          this.actualizarLoad = false;
                          this.pass = "";
                          this.UpdDocumento = null;
                          this.listarDatosConyuge();
                         }
                         if (response.estado == "failed") {
                          alert(response.mensaje);
                          this.actualizarLoad = false;
                          this.pass = "";
                          this.UpdDocumento = null;
                          this.listarDatosConyuge();
                          return false;
                         }
                         
                       }
                     );
   
   
   
             }else{
               alert("Acceso denegado");
               this.load = false;
               this.buttonStatus = false;
               this.pass = "";
               this.UpdDocumento = null;
               this.m_val.close();
               return false;
             }
   
         }, response => {console.log("POST call in error", response);},() => {
                console.log("The POST success.");
         });
         return false;
   
       
     }, (reason) => {
       console.log(`${reason}`);
       //this.closeResult = `Dismissed ${this.getDismissReason(reason)}`;
     });
   
   
   }
   
   
   //-------------------------------------------------------------------------------------------
 

}

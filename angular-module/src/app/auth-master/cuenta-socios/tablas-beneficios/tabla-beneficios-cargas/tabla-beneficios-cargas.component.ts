import { Component, OnInit, Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';

@Component({
  selector: 'app-tabla-beneficios-cargas',
  templateUrl: './tabla-beneficios-cargas.component.html',
  styleUrls: ['./tabla-beneficios-cargas.component.css']
})
export class TablaBeneficiosCargasComponent implements OnInit {

  abrirTablaBeneficiosCarga;
  abrirDocumento;

  @Input () getIdSocio;
  @Input () getNombreSocio;
  traerDatosCarga:object = [
                      'id',
                      'tipo_carga_id',
                      'rut',
                      'fecha_nacimiento',
                      'nombres',
                      'apellido_paterno',
                      'apellido_materno',
                      'direccion',
                      'celular',
                      'establecimiento',
                      'created_at',
                      'updated_at',
                  ];
  vista_tabla:boolean=false;
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
   DocumentoCarga;

   UpdDocumento;
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
    this.abrirTablaBeneficiosCarga = this.modalService.open(TablaBeneficios, { size: 'xl' });
    this.listarDatosCarga();
    this.usuario_logeado();
  }

  verDocumento(Documento,idCarga) {
    this.abrirDocumento = this.modalService.open(Documento, { size: 'lg' });
    this.traerDocumentoCarga(idCarga);
  }

  onSelectImage(event) {
    this.UpdDocumento = event.srcElement.files[0];
  }

  traerDocumentoCarga(idCarga){
    this.vista_pdf = true;
    this._SociosService.getCertificadoNacimiento(idCarga).subscribe((response) =>{
      // console.log(response);
     if(response.estado == "failed"){
       this.vista_pdf = false;
       alert(response.mensaje);
       this.abrirDocumento.close();
       return false;
     }else{
       this.DocumentoCarga = response.body[0].archivo;
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

  listarDatosCarga(){
   this._SociosService.getDatosCarga(this.getIdSocio).subscribe((response) =>{
    if(response.estado == "failed"){
      // alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
      this.vista_tabla = false;
      alert(response.mensaje);
      this.abrirTablaBeneficiosCarga.close();
      return false;
    }else{
      this.traerDatosCarga = response.body;
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
    actualizar(campo,valor,validar,id){

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
                  data.append('carga_socio_id',id);
  
                  this.actualizarLoad = true;
                  this._SociosService.ActualizarDatosCargas(data).subscribe(
                      response => {
                        if (response.estado == "success") {
                         alert(response.mensaje);
                         this.actualizarLoad = false;
                         this.pass = "";
                         this.UpdDocumento = null;
                         this.listarDatosCarga();
                        }
                        if (response.estado == "failed") {
                         alert(response.mensaje);
                         this.actualizarLoad = false;
                         this.pass = "";
                         this.UpdDocumento = null;
                         this.listarDatosCarga();
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

import { Component, OnInit, Input} from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';

@Component({
  selector: 'app-tabla-beneficios-beneficiario',
  templateUrl: './tabla-beneficios-beneficiario.component.html',
  styleUrls: ['./tabla-beneficios-beneficiario.component.css']
})
export class TablaBeneficiosBeneficiarioComponent implements OnInit {

  abrirTablaBeneficiosBeneficiario;

  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  traerDatosBeneficiario:object = [
                        'id',
                        'prioridad',
                        'relacion',
                        'rut',
                        'fecha_nacimiento',
                        'nombres',
                        'apellido_paterno',
                        'apellido_materno',
                        'direccion',
                        'celular',
                        'created_at',
                        'updated_at',

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
    this.abrirTablaBeneficiosBeneficiario = this.modalService.open(TablaBeneficios, { size: 'xl' });
    this.listarDatosBeneficiario();
    this.usuario_logeado();
  }

  limpiar_tabla(){
    this.vista_tabla = false;
  }

  listarDatosBeneficiario(){
    this._SociosService.getDatosBeneficiario(this.getIdSocio).subscribe((response) =>{
      console.log(response);
     if(response.estado == "failed"){
      //  alert('Error, El rut ingresado no existe en nuestra base de datos, pruebe digitando otro rut.');
       this.vista_tabla = false;
       alert(response.mensaje);
       this.abrirTablaBeneficiosBeneficiario.close();
       return false;
     }else{
       this.traerDatosBeneficiario = response.body;
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
                data.append('valor',valor.value);
                data.append('socio_beneficiario_id',id);

                this.actualizarLoad = true;
                this._SociosService.ActualizarDatosBeneficiarios(data).subscribe(
                    response => {
                      if (response.estado == "success") {
                       alert(response.mensaje);
                       this.actualizarLoad = false;
                       this.pass = "";
                       this.listarDatosBeneficiario();
                      }
                      if (response.estado == "failed") {
                       alert(response.mensaje);
                       this.actualizarLoad = false;
                       this.pass = "";
                       this.listarDatosBeneficiario();
                       return false;
                      }
                      
                    }
                  );



          }else{
            alert("Acceso denegado");
            this.load = false;
            this.buttonStatus = false;
            this.pass = "";
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

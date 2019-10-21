import { Component, OnInit, Input } from '@angular/core';
import { SociosService } from 'src/app/servicios/socios.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { BienestarService } from 'src/app/servicios/bienestar.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-nacimiento-bienestar',
  templateUrl: './nacimiento-bienestar.component.html',
  styleUrls: ['./nacimiento-bienestar.component.css']
})
export class NacimientoBienestarComponent implements OnInit {
  
  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  blockSocio: boolean = false;

  //variables cuenta Bienestar nacimientos
  blockIngreso: boolean = false;
  InsertarNacimiento = {
    fecha:'',
    numero_documento_1: '',
    archivo_documento_1: null,
    definicion: '',
    descripcion: '',
    monto: '',
    rut_carga:''
  }

   //varibles modal permisos usuario
   user: object=[];
   load: boolean=false;
   validarFormNacimiento = null;

   constructor(
    private _validarusuario: ValidarUsuarioService,
    private _bienestarService: BienestarService,
    config: NgbModalConfig,
    private modalService: NgbModal) 
    {
    config.backdrop = 'static';
    config.keyboard = false;
    }

  ngOnInit() {
    this.usuario_logeado();
  }

  onSelectPDF(event) {
    this.InsertarNacimiento.archivo_documento_1 = event.srcElement.files[0];
  }

  guardarCuentaBienestar() {
    if (this.InsertarNacimiento == null) {
      alert('ingrese los datos obligatorios (*)');
      return false;
    }
    this.blockIngreso = true;
    const data = new FormData();
    data.append('socio_id', this.getIdSocio);
    data.append('fecha', this.InsertarNacimiento.fecha);
    data.append('tipo_cuenta_bienestar_id', '5');
    data.append('numero_documento_1', this.InsertarNacimiento.numero_documento_1);
    data.append('archivo_documento_1', this.InsertarNacimiento.archivo_documento_1);
    data.append('monto', this.InsertarNacimiento.monto);
    data.append('definicion', this.InsertarNacimiento.definicion);
    data.append('descripcion', this.InsertarNacimiento.descripcion);
    data.append('rut', this.InsertarNacimiento.rut_carga);
    

    this._bienestarService.insertar_CuentaBienestar(data).subscribe((response) => {
      if (response.estado == 'failed') {
        alert(response.mensaje);
        this.blockIngreso = false;
        return false;
      } 
      if (response.estado == 'failed_v') {
        alert("Verifique que los campos no esten vacios, erroneos o duplicados");
        this.blockIngreso = false;
        return false;
      }
      if (response.estado == 'success') {
        // console.log("AQUI ESTOY");
        // this.InsertarNacimiento.fecha = '';
        this.InsertarNacimiento.numero_documento_1 = '';
        this.InsertarNacimiento.archivo_documento_1 = null;
        this.InsertarNacimiento.monto = '';
        this.InsertarNacimiento.definicion = '';
        this.InsertarNacimiento.descripcion = '';
        this.InsertarNacimiento.rut_carga = '';
        alert(response.mensaje);
        this.blockIngreso = false;
        return false;
      }
    },
      error => {
        console.log(error);
        this.blockIngreso = false;
        return false;
      }
    );

  }

  usuario_logeado(){
      
    this._validarusuario.usuario_logeado().subscribe((val : object ) => {
          
          this.user = val;

      }, response => {console.log("POST call in error", response);},() => {
             console.log("The POST success.");
      });
      }

      btn_validar_usuario($rut, $password, validar){//btn que esta en el modal de validacion de usuario
        this.load = true;
        const formData = new FormData();
        formData.append('rut', $rut.value);
        formData.append('password', $password.value);
        formData.append('estado', 'ingresar_cb');

        this._validarusuario.validar_usuario(formData).subscribe((val) => {

            if(val > 0){//si tiene acceso;
              
              this.load = false;
              this.guardarCuentaBienestar(); 
              this.validarFormNacimiento.close();
            }else{
              alert("Acceso denegado");
              this.load = false;
              this.validarFormNacimiento.close();
            }

        }, response => {console.log("POST call in error", response);},() => {
              console.log("The POST success.");
        });
      }



      validar_usuario(modalUsuario){
      this.validarFormNacimiento = this.modalService.open(modalUsuario, { size: 'sm' });
    
      }

}

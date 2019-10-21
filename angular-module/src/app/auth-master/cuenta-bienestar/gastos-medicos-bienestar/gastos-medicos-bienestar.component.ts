import { Component, OnInit, Input } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { BienestarService } from 'src/app/servicios/bienestar.service';

@Component({
  selector: 'app-gastos-medicos-bienestar',
  templateUrl: './gastos-medicos-bienestar.component.html',
  styleUrls: ['./gastos-medicos-bienestar.component.css']
})
export class GastosMedicosBienestarComponent implements OnInit {
  @Input () getIdSocio:'';
  @Input () getNombreSocio:'';
  @Input () getRutSocio:'';
  blockSocio: boolean = false;

  //variables cuenta Bienestar nacimientos
  blockIngreso: boolean = false;
  InsertarMedico = {
    fecha:'',
    numero_documento_1: '',
    archivo_documento_1: null,
    archivo_documento_2: null,
    definicion: '',
    descripcion: '',
    monto: ''
  }

   //varibles modal permisos usuario
   user: object=[];
   load: boolean=false;
   validarFormMedico = null;

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
    this.InsertarMedico.archivo_documento_1 = event.srcElement.files[0];
  }
  onSelectPDF2(event) {
    this.InsertarMedico.archivo_documento_2 = event.srcElement.files[0];
  }


  guardarCuentaBienestar() {
    if (this.InsertarMedico == null) {
      alert('ingrese los datos obligatorios (*)');
      return false;
    }
    this.blockIngreso = true;
    const data = new FormData();
    data.append('socio_id', this.getIdSocio);
    data.append('fecha', this.InsertarMedico.fecha);
    data.append('tipo_cuenta_bienestar_id', '7');
    data.append('numero_documento_1', this.InsertarMedico.numero_documento_1);
    data.append('archivo_documento_1', this.InsertarMedico.archivo_documento_1);
    data.append('archivo_documento_2', this.InsertarMedico.archivo_documento_2);
    data.append('monto', this.InsertarMedico.monto);
    data.append('definicion', this.InsertarMedico.definicion);
    data.append('descripcion', this.InsertarMedico.descripcion);
    data.append('rut', this.getRutSocio);
    

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
        // this.InsertarMedico.fecha = '';
        this.InsertarMedico.numero_documento_1 = '';
        this.InsertarMedico.archivo_documento_1 = null;
        this.InsertarMedico.monto = '';
        this.InsertarMedico.definicion = '';
        this.InsertarMedico.descripcion = '';
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
              this.validarFormMedico.close();
            }else{
              alert("Acceso denegado");
              this.load = false;
              this.validarFormMedico.close();
            }

        }, response => {console.log("POST call in error", response);},() => {
              console.log("The POST success.");
        });
      }



      validar_usuario(modalUsuario){
      this.validarFormMedico = this.modalService.open(modalUsuario, { size: 'sm' });
    
      }

}

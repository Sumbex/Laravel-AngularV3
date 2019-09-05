import { Component, OnInit } from '@angular/core';
import { AniosService } from 'src/app/servicios/anios.service';
import { Anios } from 'src/app/modelos/anios.model';
import { Meses } from 'src/app/modelos/meses.model';
import { Definicion } from 'src/app/modelos/definicion.model';
import { Detalle } from "src/app/modelos/detalle.model";
import { TipoCuentasService } from 'src/app/servicios/tipo-cuentas.service';
import { Sindical } from 'src/app/modelos/sindical.model';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { ValidarUsuarioService } from 'src/app/servicios/validar-usuario.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-formulario-sindical',
  templateUrl: './formulario-sindical.component.html',
  styleUrls: ['./formulario-sindical.component.css']
})
export class FormularioSindicalComponent implements OnInit {
  selectAnio: Anios[] = [];
  selectMes: Meses[] = [];
  selectDefinicion: Definicion[] = [];
  selectDetalle: Detalle[] = [];
  selectedImage:File;

  datosSindicales: Sindical ={
    fecha: '',
    nDocumento: '',
    archivoDocumento: null,
    tipoCuentaSindicato: '2',
    descripcion: '',
    definicion: '2',
    monto: null
  }
  guardarLoad:boolean=false;
//validar user 
  user:object=[];
  load:boolean=false;
  validarFormSindical = null;

  loadCajaChica:boolean = false;

  //limitar acciones
  estado;

  constructor(private _sindicalService: SindicalService,
    private _validarusuario:ValidarUsuarioService,
    config: NgbModalConfig, 
    private modalService2: NgbModal) 
    {
    config.backdrop = 'static';
    config.keyboard = false;
    }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));

    //Cargar definiciones
    this.selectDefinicion = JSON.parse(localStorage.getItem('definicion'));

    //Cargar detalles
    this.selectDetalle = JSON.parse(localStorage.getItem('detalle'));

    this.usuario_logeado();
  }

  ingresoFormulario() {
    this.guardarLoad = true;
      this._sindicalService.ingresarValor(this.datosSindicales).subscribe(
        response => {
          if(response.estado == "success"){
            this.datosSindicales.fecha = '';
            this.datosSindicales.nDocumento = '';
            this.datosSindicales.descripcion = '';
            this.datosSindicales.monto = null;
            alert(response.mensaje);  
            this.guardarLoad = false;

          }if(response.estado == "failed"){
            alert(response.mensaje);
            this.guardarLoad = false;

          }if(response.estado == "failed_v"){
            alert("error de ingreso, verifique que los datos esten bien ingresados y no duplicados ya en la base de datos.");
            this.guardarLoad = false;
        }
        error => {
          console.log(<any>error);
        }
      }
    ); 
  }

  onSelectImage(event) {
    this.datosSindicales.archivoDocumento = event.srcElement.files[0];
  }

  changeDefinicion(evento){
    this.datosSindicales.definicion = evento.target.value;
  }

  changeDetalle(evento){
    this.datosSindicales.tipoCuentaSindicato = evento.target.value;
    if(this.datosSindicales.tipoCuentaSindicato == '3'){
      var anio = this.datosSindicales.fecha.substring(0,4);
      var mes = this.datosSindicales.fecha.substring(5,7);
      this.loadCajaChica = true;
      this._sindicalService.getCalcularCajaChica(anio,mes).subscribe(
        response => {
          //console.log(response);
          if(response.estado == "success"){
            this.datosSindicales.monto = response.monto;
            this.loadCajaChica = false;
          }else{
            this.datosSindicales.monto = null;
            this.loadCajaChica = false;
          }
        },
        error => {
          console.log(<any>error);
        }
      );
    }else{
      this.datosSindicales.monto = null;
    }
  }

  //validar usuario
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
        formData.append('estado', 'ingresar_cs');

        this._validarusuario.validar_usuario(formData).subscribe((val) => {

            if(val > 0){//si tiene acceso;
              
              this.load = false;
              this.ingresoFormulario(); 
              this.validarFormSindical.close();
            }else{
              alert("Acceso denegado");
              this.load = false;
              this.validarFormSindical.close();
            }

        }, response => {console.log("POST call in error", response);},() => {
              console.log("The POST success.");
        });
      }



      validar_usuario(modalUsuario){
      //var passwordVacio = <HTMLInputElement>document.getElementById('password');
      //passwordVacio.value ='';
      this.validarFormSindical = this.modalService2.open(modalUsuario, { size: 'sm' });
    
      }

}

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
//validar user 
  user:object=[];
  load:boolean=false;
  validarFormSindical = null;

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
    console.log(this.selectDefinicion);
    console.log(this.selectDetalle);

    this.usuario_logeado();
  }

  ingresoFormulario() {
      this._sindicalService.ingresarValor(this.datosSindicales).subscribe(
        response => {
          console.log(response);
          this.datosSindicales.fecha = '';
          this.datosSindicales.nDocumento = '';
          this.datosSindicales.descripcion = '';
          this.datosSindicales.monto = null;
          alert("Guardado con exito");
        },
        error => {
          console.log(<any>error);
        }
      );
    }
  

  onSelectImage(event) {
    this.datosSindicales.archivoDocumento = event.srcElement.files[0];
  }

  changeDefinicion(evento){
    this.datosSindicales.definicion = evento.target.value;
    console.log("ID Definicion: " + this.datosSindicales.definicion);
  }

  changeDetalle(evento){
    this.datosSindicales.tipoCuentaSindicato = evento.target.value;
    console.log("ID Detalle: " + this.datosSindicales.tipoCuentaSindicato);
    if(this.datosSindicales.tipoCuentaSindicato == '3'){
      var anio = this.datosSindicales.fecha.substring(0,4);
      var mes = this.datosSindicales.fecha.substring(5,7);
      this._sindicalService.getCalcularCajaChica(anio,mes).subscribe(
        response => {
          //console.log(response);
          if(response.estado == "success"){
            this.datosSindicales.monto = response.monto;
          }else{
            this.datosSindicales.monto = null;
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
      this.validarFormSindical = this.modalService2.open(modalUsuario, { size: 'sm' });
      }

}

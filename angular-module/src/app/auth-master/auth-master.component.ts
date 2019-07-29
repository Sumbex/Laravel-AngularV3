import { Component, OnInit } from '@angular/core';
import { TipoCuentasService } from '../servicios/tipo-cuentas.service';
import { AniosService } from '../servicios/anios.service';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { map } from 'rxjs/operators';
import { UsuarioService } from '../servicios/usuarios.service';

@Component({
  selector: 'app-auth-master',
  templateUrl: './auth-master.component.html',
  styleUrls: ['./auth-master.component.css']
})
export class AuthMasterComponent implements OnInit {
  anios;
  filtroDefiniciones;
  filtroDetalle;
  filtroAnios;
  filtroMeses;

  //Tiempo fuerra Loading
  tiempoEspera: number = 20;
  tiempoEsperaToken: number = 1;
  test2 = 1000;
  titleMensaje = 'Iniciando el sistema';
  bodyMensaje = 'Espere unos segundos mientras carga el sistema';

  constructor(private _tipoCuentas: TipoCuentasService, private _getAnios: AniosService, private _usuariosService: UsuarioService , private config: NgbModalConfig, private modalService: NgbModal) {

    config.backdrop = 'static';
    config.keyboard = false;  
    
   }

  ngOnInit() {
    //Guardar definicion
    this._tipoCuentas.getDefinicion().subscribe((res) => {
      this.filtroDefiniciones = res.map(({id,descripcion}) => ({id,descripcion}));
      localStorage.setItem('definicion', JSON.stringify(this.filtroDefiniciones));
    });
    //Guardar Detalle
    this._tipoCuentas.getTipoCuenta().subscribe((res) => {
      this.filtroDetalle = res.map(({id,descripcion}) => ({id,descripcion}));
      localStorage.setItem('detalle', JSON.stringify(this.filtroDetalle));
    });
    //Guardar Anios
    this._getAnios.getAnios().subscribe((res) => {
      this.filtroAnios = res.map(({id,descripcion}) => ({id,descripcion}));
      localStorage.setItem('anios', JSON.stringify(this.filtroAnios));
    });
    //Guardar Meses
    this._getAnios.getMeses().subscribe((res) => {
      this.filtroMeses = res.map(({id,descripcion}) => ({id,descripcion}));
      localStorage.setItem('meses', JSON.stringify(this.filtroMeses));
      document.getElementById("closeModalButton").click();
    });

    document.getElementById("openModalButton").click();
    this.startTimer();
    this.startTimerToken();
    this.verificarCarga();
  }

  verificarCarga(){
    if(localStorage.getItem('definicion') === null && localStorage.getItem('detalle') === null && localStorage.getItem('anios') === null && localStorage.getItem('meses') === null){
      console.log("Se estan cargando los datos en memoria");
    }
  }

  open(content) {
    this.modalService.open(content, {centered:true});
  }

  //Timer para loading en caso de superar el limite de espera
  startTimer() {
    setInterval(() => {
        if(this.tiempoEspera > 0) {
          this.tiempoEspera--;
        } else {
          this.titleMensaje = 'Error al cargar';
          this.bodyMensaje = 'Se ha superado el tiempo de espera, por favor compruebe su conexión a internet y refresque esta pagina';
        }
      },1000)
    }

  startTimerToken(){
    setInterval(() => {
      if(this.tiempoEsperaToken > 0){
        this.tiempoEsperaToken--;
      }else{
        let estadoToken = this._usuariosService.isAuthenticated();
        if(estadoToken == false){
          window.location.reload();
        }else{
          this.tiempoEsperaToken = 1;
        }
      }
    },1000)
  }
}
import { Component, OnInit } from '@angular/core';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { UsuarioService } from 'src/app/servicios/usuarios.service';
import { LiquidacionEmpanadaService } from 'src/app/servicios/liquidacion-empanada.service';

@Component({
  selector: 'app-crear-empleado',
  templateUrl: './crear-empleado.component.html',
  styleUrls: ['./crear-empleado.component.css']
})
export class CrearEmpleadoComponent implements OnInit {

  empleadoForm = {
    rut_trabajador: '',
    nombre_trabajador: '',
    cargo: '',
    nombre_afp: '',
    nombre_isapre: '',
    rut_empresa: '',
    nombre_empresa: '',
    direccion_empresa: '',
  }
  // MENSAJES DE ALERTAS
  empleadoCorrecto: '';
  empleadoError: '';
  empleadoTraer: '';
  actualizarEmpleadoCorrecto: '';
  actualizarEmpleadoError: '';
  erroresAct = [];

  // OCULTAR VARIABLES
  alertError;
  cargandoTabla;
  mod_editar = null;

  constructor(
    config: NgbModalConfig,
    private modalService: NgbModal,
    private _usuariosSevice: UsuarioService,
    private _liquidacionEmpanadaService: LiquidacionEmpanadaService) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.traerEmpleados();
  }

  modal_editar(modal) {
    this.mod_editar = this.modalService.open(modal, { size: 'lg' });
  }

  cerrar_editar() {
    this.mod_editar.close();
    // this.traerEmpleados();
  }

  crearEmpleado(form) {
    this._liquidacionEmpanadaService.crearEmpleado(this.empleadoForm).subscribe(response => {
      if (response.estado == 'success') {
        form.reset();
        this.empleadoCorrecto = response.mensaje;
        alert(this.empleadoCorrecto);
        this.traerEmpleados();
      } if (response.estado == 'failed') {
        this.empleadoError = response.mensaje;
        alert(this.empleadoError);
      }
    }, error => {
      console.log(error);
    });
  }

  traerEmpleados() {
    this.cargandoTabla = true;
    this._liquidacionEmpanadaService.traerEmpleados().subscribe(response => {
      if (response.estado == 'success') {
        this.empleadoTraer = response.empleado;
        this.cargandoTabla = false;
      }
    }, error => {
      console.log(error);
    });
  }

  actualizarEmpleado(idEmpleado, actualizarEmpleado) {
    this._liquidacionEmpanadaService.actualizarEmpleado(idEmpleado, actualizarEmpleado).subscribe(response => {

      if (response.estado == 'success') {
        this.actualizarEmpleadoCorrecto = response.mensaje;
        alert(this.actualizarEmpleadoCorrecto);
        this.alertError = false;
        this.mod_editar.close();
        this.traerEmpleados();
      }
      if (response.estado == 'failed') {
        this.actualizarEmpleadoError = response.mensaje;
        alert(this.actualizarEmpleadoError);
      }
      if (response.estado == 'failed_v') {
        for (let index = 0; index < Object.keys(response.mensaje).length; index++) {
          this.alertError = true;
          this.erroresAct = (response.mensaje[Object.keys(response.mensaje)[index]]);
        }
      }

    }, error => {
      console.log(error);
    });
  }

}

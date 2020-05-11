import { Component, OnInit } from '@angular/core';
import { LiquidacionJuanitoService } from 'src/app/servicios/liquidacion-juanito.service';
import { LiquidacionMarroService } from 'src/app/servicios/liquidacion-marro.service';

@Component({
  selector: 'app-liquidacion',
  templateUrl: './liquidacion.component.html',
  styleUrls: ['./liquidacion.component.css']
})
export class LiquidacionComponent implements OnInit {

  //Variable para los empleados del select
  empleado = '';
  empleados = [];
  nombreTrabajador = '';
  rutTrabajador = '';
  saludTrabajador = '';
  previsionTrabajador = '';

  // Fecha de emisión
  fechaEmision = '';

  // Variables para guardar lso haberes y los descuentos
  haberes = [];
  descuentos = [];

  // Variables desglosadas de los haberes y descuentos
  haberesLista = {
    sueldoBase: [],
    horasExtras: [],
    gratificacionLegal: [],
    bonoLocomocion: [],
    bonoColacion: [],
    asignacionFamiliar: [],
    viatico: [],
    bonoExtra: [],
    vacaciones: [],
    viaticoGestion: [],
    feriadoProporcional: [],
    tarjetaCelular: [],
    diaDomingo: []
  }

  descuentosLista = {
    cotizacionAfp: [],
    saludInp: [],
    planSalud: [],
    seguroCesantia: [],
    anticipo: [],
    prestamo: [],
    bonoTrimestral: [],
    feriadoProporcional: []
  }

  // Variable para ocultar la liquidacion
  blockIngreso = true;
  counter = 0;
  showTable = false;
  counterLiquidacion = 0;
  load = false;
  loadTable = false;
  fecha = '';
  blockIngresoGeneracion = false;

  // Variable para traer la lista de liquidaciones generadas
  liquidacionesGeneradas = [];

  constructor(private _liq: LiquidacionJuanitoService, private _confLiq: LiquidacionMarroService) { }

  ngOnInit() {
    this.traerEmpleados();
  }

  traerEmpleados() {
    this._liq.traer_empleados().subscribe(response => {
      if (response.estado == "success") {
        this.empleados = response.empleado;
      }
      error => {
        console.log(error);
      }
    }
    );
  }

  traerDatosLiquidacion(test) {
    if (test.target.options.selectedIndex != 0) {
      this.previsionTrabajador = this.empleados[test.target.options.selectedIndex - 1].nombre_afp;
      this.saludTrabajador = this.empleados[test.target.options.selectedIndex - 1].nombre_isapre;
      this.rutTrabajador = this.empleados[test.target.options.selectedIndex - 1].rut_trabajador;
    }
    this.showTable = false;
    this.nombreTrabajador = test.target.options[test.target.options.selectedIndex].text;
    this.load = true;
    this.liquidacionesGeneradas = [];
    this.blockIngreso = true;
    this.counter = 0;
    this.getLiquidacionesGeneradas();
    this.getConfiguracionHaberesPorIdEmpleado();
    this.getConfiguracionDescuentosPorIdEmpleado();
  }

  getLiquidacionesGeneradas() {
    this._confLiq.getLiquidacionesGeneradasPorIdEmpleado(this.empleado).subscribe(response => {
      if (response.estado == 'success') {
        this.liquidacionesGeneradas = response.liquidaciones;
        this.load = false;
      } else {
        alert(response.mensaje);
        this.load = false;
      }
    }, error => {
      console.log(error);
    });
  }

  getConfiguracionHaberesPorIdEmpleado() {
    this._confLiq.getConfiguracionHaberesPorIdEmpleado(this.empleado).subscribe(response => {
      if (response.estado == 'success') {
        this.haberes = response.haberes;
        this.separarHaberes();
        this.counter++;
        if (this.counter == 2) {
          this.blockIngreso = false;
        }
      } else {
        alert(response.mensaje);
      }
    }, error => {
      console.log(error);
    });
  }

  getConfiguracionDescuentosPorIdEmpleado() {
    this._confLiq.getConfiguracionDescuentosPorIdEmpleado(this.empleado).subscribe(response => {
      if (response.estado == 'success') {
        this.descuentos = response.descuentos;
        this.separarDescuentos();
        this.counter++;
        if (this.counter == 2) {
          this.blockIngreso = false;
        }
      } else {
        alert(response.mensaje);
      }
    }, error => {
      console.log(error);
    });
  }

  separarHaberes() {
    // guardar sueldo base
    this.haberesLista.sueldoBase = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 1);
    this.haberesLista.sueldoBase = this.haberesLista.sueldoBase[0];
    // guardar horas extras
    this.haberesLista.horasExtras = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 2);
    this.haberesLista.horasExtras = this.haberesLista.horasExtras[0];
    // gratificacion legal
    this.haberesLista.gratificacionLegal = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 3);
    this.haberesLista.gratificacionLegal = this.haberesLista.gratificacionLegal[0];
    // bono locomocion
    this.haberesLista.bonoLocomocion = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 4);
    this.haberesLista.bonoLocomocion = this.haberesLista.bonoLocomocion[0];
    // bono colacion
    this.haberesLista.bonoColacion = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 5);
    this.haberesLista.bonoColacion = this.haberesLista.bonoColacion[0];
    // asignacion familiar
    this.haberesLista.asignacionFamiliar = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 6);
    this.haberesLista.asignacionFamiliar = this.haberesLista.asignacionFamiliar[0];
    // viatico
    this.haberesLista.viatico = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 7);
    this.haberesLista.viatico = this.haberesLista.viatico[0];
    // Bono Extra
    this.haberesLista.bonoExtra = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 8);
    this.haberesLista.bonoExtra = this.haberesLista.bonoExtra[0];
    // Vacaciones
    this.haberesLista.vacaciones = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 9);
    this.haberesLista.vacaciones = this.haberesLista.vacaciones[0];
    // Viatico por Gestion
    this.haberesLista.viaticoGestion = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 10);
    this.haberesLista.viaticoGestion = this.haberesLista.viaticoGestion[0];
    // Feriado Proporcional
    this.haberesLista.feriadoProporcional = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 11);
    this.haberesLista.feriadoProporcional = this.haberesLista.feriadoProporcional[0];
    // Tarjeta Celular
    this.haberesLista.tarjetaCelular = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 12);
    this.haberesLista.tarjetaCelular = this.haberesLista.tarjetaCelular[0];
    // Dia domingo
    this.haberesLista.diaDomingo = this.haberes.filter(filtro => filtro.cs_lista_haberes_id == 13);
    this.haberesLista.diaDomingo = this.haberesLista.diaDomingo[0];

    console.log(this.haberesLista);
  }

  separarDescuentos() {
    // cotizacionAFP
    this.descuentosLista.cotizacionAfp = this.descuentos.filter(filtro => filtro.cs_lista_descuentos_id == 1);
    this.descuentosLista.cotizacionAfp = this.descuentosLista.cotizacionAfp[0];
    // Salud INP
    this.descuentosLista.saludInp = this.descuentos.filter(filtro => filtro.cs_lista_descuentos_id == 2);
    this.descuentosLista.saludInp = this.descuentosLista.saludInp[0];
    // Plan de salud
    this.descuentosLista.planSalud = this.descuentos.filter(filtro => filtro.cs_lista_descuentos_id == 3);
    this.descuentosLista.planSalud = this.descuentosLista.planSalud[0];
    // Seguro de Cesantia
    this.descuentosLista.seguroCesantia = this.descuentos.filter(filtro => filtro.cs_lista_descuentos_id == 4);
    this.descuentosLista.seguroCesantia = this.descuentosLista.seguroCesantia[0];
    // Anticipo
    this.descuentosLista.anticipo = this.descuentos.filter(filtro => filtro.cs_lista_descuentos_id == 5);
    this.descuentosLista.anticipo = this.descuentosLista.anticipo[0];
    // Prestamo
    this.descuentosLista.prestamo = this.descuentos.filter(filtro => filtro.cs_lista_descuentos_id == 6);
    this.descuentosLista.prestamo = this.descuentosLista.prestamo[0];
    // Bono Trimestral
    this.descuentosLista.bonoTrimestral = this.descuentos.filter(filtro => filtro.cs_lista_descuentos_id == 7);
    this.descuentosLista.bonoTrimestral = this.descuentosLista.bonoTrimestral[0];
    // Feriado Proporcional
    this.descuentosLista.feriadoProporcional = this.descuentos.filter(filtro => filtro.cs_lista_descuentos_id == 8);
    this.descuentosLista.feriadoProporcional = this.descuentosLista.feriadoProporcional[0];
  }

  // VARIABLE PARA GUARDAR LA LIQUIDACION
  setLiquidacionHistorial() {
    this.blockIngresoGeneracion = true;
    this._confLiq.setHistorialLiquidacion(this.empleado, this.fechaEmision, this.haberes, this.descuentos).subscribe(response => {
      if (response.estado == 'success') {
        alert(response.mensaje);
        this.blockIngresoGeneracion = false;
        this.getLiquidacionesGeneradas();
      } else {
        alert(response.mensaje);
        this.blockIngresoGeneracion = false;
      }
    }, error => {
      console.log(error);
      this.blockIngresoGeneracion = false;
    });
  }

  // Cargar la hoja con los datos seleccionados
  test(id, fecha) {
    this.fecha = fecha;
    this.counterLiquidacion = 0;
    this.showTable = false;
    this.loadTable = true;
    this.getHaberesPorIdLiquidacion(id);
    this.getDescuentosPorIdLiquidacion(id);
  }

  getHaberesPorIdLiquidacion(idLiquidacion) {
    this._confLiq.getHaberesPorIdLiquidacion(idLiquidacion).subscribe(response => {
      if (response.estado == 'success') {
        this.haberes = response.haberes;
        this.separarHaberes();
        this.counterLiquidacion++;
        if (this.counterLiquidacion == 2) {
          this.showTable = true;
          this.loadTable = false;
        }
      } else {
        alert(response.mensaje);
      }
    }, error => {
      console.log(error);
    });
  }

  getDescuentosPorIdLiquidacion(idLiquidacion) {
    this._confLiq.getDescuentosPorIdLiquidacion(idLiquidacion).subscribe(response => {
      if (response.estado == 'success') {
        this.descuentos = response.descuentos;
        this.separarDescuentos();
        this.counterLiquidacion++;
        if (this.counterLiquidacion == 2) {
          this.showTable = true;
          this.loadTable = false;
        }
      } else {
        alert(response.mensaje);
      }
    }, error => {
      console.log(error);
    });
  }

}

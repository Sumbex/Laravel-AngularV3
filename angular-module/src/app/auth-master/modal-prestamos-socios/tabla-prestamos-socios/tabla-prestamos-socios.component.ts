import { Component, OnInit } from '@angular/core';
import { Meses } from 'src/app/modelos/meses.model';
import { Anios } from 'src/app/modelos/anios.model';
import { SindicalService } from 'src/app/servicios/sindical.service';
import { AniosService } from 'src/app/servicios/anios.service';
import { HttpClient } from '@angular/common/http';
import { Prestamos } from 'src/app/modelos/prestamos.model';
import { NgbModalConfig, NgbModal } from '@ng-bootstrap/ng-bootstrap';

@Component({
  selector: 'app-tabla-prestamos-socios',
  templateUrl: './tabla-prestamos-socios.component.html',
  styleUrls: ['./tabla-prestamos-socios.component.css']
})
export class TablaPrestamosSociosComponent implements OnInit {

  //objetos con valores de los select para ser manipulados
  selectAnio = {
    id: '1',
    descripcion: ''
  }

  selectMes = {
    id: '1',
    descripcion: ''
  }

  modalActualizarPagoSalud = null;

  //variable que almacena todos los prestamos que tiene el servidor
  valoresPrestamosSalud;
  valoresPrestamosApuro;
  valoresPrestamosAporte
  montoDelInteresPagar;
  montoCuotaPagar;
  montoFinalPagar;

  constructor(private _sindicalService: SindicalService,
     private _fechasService: AniosService,
     config: NgbModalConfig, 
     private modalService: NgbModal) {
      config.backdrop = 'static';
      config.keyboard = false;
      }

  ngOnInit() {
    //Cargar Años
    this.selectAnio = JSON.parse(localStorage.getItem('anios'));
    this.selectAnio.id = '1';

    //Cargar Meses
    this.selectMes = JSON.parse(localStorage.getItem('meses'));
    this.selectMes.id = '1';

    //Cargar id del Año actual
    this._fechasService.getAnioActual().subscribe(
      response => {
        let AnioActual;
        AnioActual = response;
        this.selectAnio.id = AnioActual.id;
      },
      error => {
        console.log(error);
      }
    )

    //Cargar id del Mes actual
    this._fechasService.getMesActual().subscribe(
      response => {
        let mesActual;
        mesActual = response;
        this.selectMes.id = mesActual.id;
        this.refrescarTablaPrestamosClientes();
      },
      error => {
        console.log(error);
      }
    )
  }

  openActualizar(Actualizar, interes, totalPrestamoNoInteres, totalPrestamo, cuotaP) {
    this.modalActualizarPagoSalud = this.modalService.open(Actualizar,{ size: 'sm' });

    this.montoDelInteresPagar = interes / cuotaP;
    this.montoCuotaPagar = totalPrestamoNoInteres / cuotaP;
    this.montoFinalPagar = totalPrestamo / cuotaP;
   }

   cerrarActualizar(){
    this.modalActualizarPagoSalud.close();
  }

  refrescarTablaPrestamosClientes() {
    this._sindicalService.getPrestramosSocios(this.selectAnio.id, this.selectMes.id).subscribe(
      response => {
        if (response.estado == "failed" || response.estado == "failed_v") {
          this.valoresPrestamosSalud = null;
          this.valoresPrestamosApuro = null;
          this.valoresPrestamosAporte = null;
          alert(response.mensaje);
        } else {
          this.valoresPrestamosSalud = response.salud;
          this.valoresPrestamosApuro = response.apuro;
          this.valoresPrestamosAporte = response.aporte;
          console.log(this.valoresPrestamosSalud);
          console.log(this.valoresPrestamosSalud);
          console.log(this.valoresPrestamosAporte);
        }
      },
      error => {
        console.log(error);
      }
    )
  }

  changeAnio(evento) {
    this.selectAnio.id = evento.target.value;
    console.log(this.selectAnio.id);
    this.refrescarTablaPrestamosClientes();
  }

  changeMes(evento) {
    this.selectMes.id = evento.target.value;
    console.log(this.selectMes.id);
    this.refrescarTablaPrestamosClientes();
  }

  pagarPrestamo(fecha, prestamoId, montoPagar){
    this._sindicalService.pagarPrestamo(fecha.value, prestamoId, montoPagar.value).subscribe(
      response => {
        console.log("Pagado con éxito creo");
      },
      error => {
        console.log(error);
      }
    )
  }
}

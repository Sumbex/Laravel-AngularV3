import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { TablaDesvinculadosConsorcioComponent } from './tabla-desvinculados-consorcio.component';

describe('TablaDesvinculadosConsorcioComponent', () => {
  let component: TablaDesvinculadosConsorcioComponent;
  let fixture: ComponentFixture<TablaDesvinculadosConsorcioComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ TablaDesvinculadosConsorcioComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(TablaDesvinculadosConsorcioComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});

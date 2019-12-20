import { TestBed } from '@angular/core/testing';

import { LeyesService } from './leyes.service';

describe('LeyesService', () => {
  beforeEach(() => TestBed.configureTestingModule({}));

  it('should be created', () => {
    const service: LeyesService = TestBed.get(LeyesService);
    expect(service).toBeTruthy();
  });
});
